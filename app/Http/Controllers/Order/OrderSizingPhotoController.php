<?php

namespace App\Http\Controllers\Order;

use App\Enums\PhotoType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderSizingPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class OrderSizingPhotoController extends Controller
{
    /**
     * POST /order/sizing-photos
     *
     * Accepts an array of blobs (from the camera state machine) or file uploads.
     * Each photo is associated with a photo_type (fingers|thumb|fingers_other|thumb_other).
     * The order UUID comes from session('order_form.sizing_session_id') — set here if not yet set.
     *
     * At this point in the flow we may not have a real Order row yet (the order is created
     * in OrderController@store after step 3). We store photos in a temp session directory
     * keyed by a sizing session ULID and attach them to the order on creation.
     */
    public function store(Request $request): JsonResponse
    {
        // Two accepted payloads:
        //  • multipart `photos[]` files (normal path)
        //  • JSON/form `photos_base64[]` data URLs — the camera page retries with
        //    this when a phone browser mangles the multipart upload (seen on
        //    iPhone Chrome: request rejected before reaching Laravel).
        $isBase64 = $request->has('photos_base64');

        $validator = Validator::make($request->all(), $isBase64
            ? [
                'photos_base64'   => ['required', 'array', 'min:1', 'max:4'],
                'photos_base64.*' => ['required', 'string', 'max:15000000'], // ~11 MB of image bytes
                'photo_types'     => ['required', 'array', 'min:1', 'max:4'],
                'photo_types.*'   => ['required', 'in:fingers,thumb,fingers_other,thumb_other'],
            ]
            : [
                'photos'        => ['required', 'array', 'min:1', 'max:4'],
                'photos.*'      => ['required', 'file', 'mimes:jpeg,jpg,png,heic,heif,webp', 'max:8192'],
                'photo_types'   => ['required', 'array', 'min:1', 'max:4'],
                'photo_types.*' => ['required', 'in:fingers,thumb,fingers_other,thumb_other'],
            ]);

        if ($validator->fails()) {
            Log::warning('Sizing photo upload rejected', [
                'mode'   => $isBase64 ? 'base64' : 'multipart',
                'errors' => $validator->errors()->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'We couldn\'t read your photos. Please retake them and try again.',
            ], 422);
        }

        // Generate or reuse a sizing session ID.
        if (! session('order_form.sizing_session_id')) {
            session(['order_form.sizing_session_id' => Str::ulid()]);
        }
        $sessionId = session('order_form.sizing_session_id');

        $sources    = $isBase64 ? $request->input('photos_base64') : $request->file('photos');
        $photoTypes = $request->input('photo_types');
        $manager    = new ImageManager(new Driver());
        $dir        = "sizing/temp/{$sessionId}";
        $storedPaths = [];

        // Ensure directory exists before saving on the PRIVATE disk.
        Storage::disk('local')->makeDirectory($dir);

        foreach (array_values($sources) as $index => $source) {
            $type     = $photoTypes[$index] ?? 'fingers';
            $filename = Str::ulid() . '.jpg';
            $fullPath = storage_path("app/private/{$dir}/{$filename}");

            try {
                $input = $isBase64 ? self::decodeDataUrl($source) : $source->getRealPath();
                // Strip EXIF + convert HEIC → JPEG using Intervention Image.
                $manager->read($input)->toJpeg(92)->save($fullPath);
            } catch (\Throwable $e) {
                Log::warning('Sizing photo could not be decoded', ['type' => $type, 'error' => $e->getMessage()]);

                return response()->json([
                    'success' => false,
                    'message' => 'One of your photos couldn\'t be processed. Please retake it and try again.',
                ], 422);
            }

            $storedPaths[] = [
                'path'       => "{$dir}/{$filename}",
                'photo_type' => $type,
                'mime_type'  => 'image/jpeg',
                'file_size'  => filesize($fullPath) ?: null,
            ];
        }

        // Store pending photo paths in session — attached to order on OrderController@store.
        session(['order_form.sizing_photos' => $storedPaths]);
        session(['order_form.sizing_method' => 'live_camera']);

        return response()->json(['success' => true, 'count' => count($storedPaths)]);
    }

    /** Raw image bytes from a `data:image/...;base64,` URL. */
    private static function decodeDataUrl(string $dataUrl): string
    {
        if (! preg_match('#^data:image/(jpeg|jpg|png|webp);base64,#', $dataUrl, $m)) {
            throw new \InvalidArgumentException('Not an image data URL');
        }

        $bytes = base64_decode(substr($dataUrl, strlen($m[0])), true);
        if ($bytes === false || $bytes === '') {
            throw new \InvalidArgumentException('Invalid base64 image');
        }

        return $bytes;
    }

    /**
     * Attach session-stored sizing photos to a newly created order.
     * Called from OrderController@store after the order row exists.
     */
    public static function attachToOrder(Order $order): void
    {
        $photos = session('order_form.sizing_photos', []);

        foreach ($photos as $photo) {
            // Move from temp dir to permanent order dir on the PRIVATE disk.
            $filename = basename($photo['path']);
            $newPath  = "sizing/{$order->id}/{$filename}";

            if (Storage::disk('local')->exists($photo['path'])) {
                Storage::disk('local')->makeDirectory("sizing/{$order->id}");
                Storage::disk('local')->move($photo['path'], $newPath);
            }

            OrderSizingPhoto::create([
                'order_id'    => $order->id,
                'path'        => $newPath,
                'photo_type'  => $photo['photo_type'],
                'mime_type'   => $photo['mime_type'] ?? 'image/jpeg',
                'file_size'   => $photo['file_size'] ?? null,
                'uploaded_at' => now(),
            ]);
        }

        session()->forget(['order_form.sizing_photos', 'order_form.sizing_session_id']);
    }
}
