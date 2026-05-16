<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPaymentProof;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class OrderPaymentProofController extends Controller
{
    /**
     * POST /order/{order}/proof
     *
     * Accepts a payment screenshot or PDF.
     * Files are stored on the PRIVATE `local` disk — never web-accessible.
     * Image uploads are passed through Intervention to strip EXIF and
     * normalize HEIC/HEIF → JPEG. PDFs are saved as-is.
     *
     * Auth: the visitor's session must have authorized this order
     * (either by placing it or by passing the tracking lookup challenge).
     */
    public function store(Request $request, string $orderId): JsonResponse
    {
        if (! OrderController::sessionMayViewOrder($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Please look up your order before uploading a payment proof.',
            ], 403);
        }

        $order = Order::findOrFail($orderId);

        $request->validate([
            'proof' => ['required', 'file', 'mimes:jpeg,jpg,png,heic,heif,webp,pdf', 'max:8192'],
        ]);

        $file = $request->file('proof');
        $mime = strtolower($file->getMimeType() ?? '');
        $dir  = "payment-proofs/{$order->id}";

        Storage::disk('local')->makeDirectory($dir);

        if ($mime === 'application/pdf') {
            $filename  = Str::ulid() . '.pdf';
            $storedMime = 'application/pdf';
            Storage::disk('local')->putFileAs($dir, $file, $filename);
            $fileSize = Storage::disk('local')->size("{$dir}/{$filename}");
        } else {
            $filename   = Str::ulid() . '.jpg';
            $storedMime = 'image/jpeg';
            $manager    = new ImageManager(new Driver());
            $image      = $manager->read($file->getRealPath())->toJpeg(88);
            $fullPath   = storage_path("app/private/{$dir}/{$filename}");
            $image->save($fullPath);
            $fileSize   = filesize($fullPath) ?: null;
        }

        OrderPaymentProof::create([
            'order_id'    => $order->id,
            'path'        => "{$dir}/{$filename}",
            'mime_type'   => $storedMime,
            'file_size'   => $fileSize,
            'is_advance'  => false,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Got your receipt — I'll verify it within a few hours and WhatsApp you when it's confirmed.",
        ]);
    }
}
