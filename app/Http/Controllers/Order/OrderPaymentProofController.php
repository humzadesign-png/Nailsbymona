<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPaymentProof;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderPaymentProofController extends Controller
{
    /**
     * POST /order/{order}/proof
     *
     * Accepts a payment screenshot or PDF.
     * Returns JSON for the AJAX upload flow on the confirmation page.
     */
    public function store(Request $request, string $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'proof' => ['required', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:8192'],
        ]);

        $file     = $request->file('proof');
        $ext      = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::ulid() . '.' . $ext;
        $dir      = "payment-proofs/{$order->id}";

        // Ensure directory exists.
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($dir);

        // Store the file.
        $path = $file->storeAs($dir, $filename, 'public');

        OrderPaymentProof::create([
            'order_id'    => $order->id,
            'path'        => $path,
            'mime_type'   => $file->getMimeType(),
            'file_size'   => $file->getSize(),
            'is_advance'  => false,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Got your receipt — I'll verify it within a few hours and WhatsApp you when it's confirmed.",
        ]);
    }
}
