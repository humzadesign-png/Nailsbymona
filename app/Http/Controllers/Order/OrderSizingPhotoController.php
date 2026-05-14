<?php

namespace App\Http\Controllers\Order;

use App\Enums\PhotoType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderSizingPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $request->validate([
            'photos'      => ['required', 'array', 'min:1', 'max:4'],
            'photos.*'    => ['required', 'file', 'mimes:jpeg,jpg,png,heic,heif,webp', 'max:8192'],
            'photo_types' => ['required', 'array', 'min:1', 'max:4'],
            'photo_types.*' => ['required', 'in:fingers,thumb,fingers_other,thumb_other'],
        ]);

        // Generate or reuse a sizing session ID.
        if (! session('order_form.sizing_session_id')) {
            session(['order_form.sizing_session_id' => Str::ulid()]);
        }
        $sessionId = session('order_form.sizing_session_id');

        $storedPaths = [];
        $photos      = $request->file('photos');
        $photoTypes  = $request->input('photo_types');
        $manager     = new ImageManager(new Driver());

        foreach ($photos as $index => $file) {
            $type     = $photoTypes[$index] ?? 'fingers';
            $filename = Str::ulid() . '.jpg';
            $dir      = "sizing/temp/{$sessionId}";

            // Ensure directory exists before saving.
            Storage::disk('public')->makeDirectory($dir);

            // Strip EXIF + convert HEIC → JPEG using Intervention Image.
            $image = $manager->read($file)->toJpeg(92);
            $image->save(storage_path("app/public/{$dir}/{$filename}"));

            $storedPaths[] = [
                'path'       => "{$dir}/{$filename}",
                'photo_type' => $type,
                'mime_type'  => 'image/jpeg',
                'file_size'  => filesize(storage_path("app/public/{$dir}/{$filename}")),
            ];
        }

        // Store pending photo paths in session — attached to order on OrderController@store.
        session(['order_form.sizing_photos' => $storedPaths]);
        session(['order_form.sizing_method' => 'live_camera']);

        return response()->json(['success' => true, 'count' => count($storedPaths)]);
    }

    /**
     * Attach session-stored sizing photos to a newly created order.
     * Called from OrderController@store after the order row exists.
     */
    public static function attachToOrder(Order $order): void
    {
        $photos = session('order_form.sizing_photos', []);

        foreach ($photos as $photo) {
            // Move from temp dir to permanent order dir.
            $sessionId = session('order_form.sizing_session_id');
            $filename  = basename($photo['path']);
            $newPath   = "sizing/{$order->id}/{$filename}";

            if (file_exists(storage_path("app/public/{$photo['path']}"))) {
                Storage::disk('public')->makeDirectory("sizing/{$order->id}");
                Storage::disk('public')->move($photo['path'], $newPath);
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
