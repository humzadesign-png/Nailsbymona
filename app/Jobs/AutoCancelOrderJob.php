<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\OrderCancelled;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoCancelOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(private readonly string $orderId) {}

    public function handle(): void
    {
        $order = Order::with(['items', 'paymentProofs'])->find($this->orderId);

        // Only cancel if still awaiting payment AND no proof uploaded
        if (! $order
            || $order->payment_status !== PaymentStatus::Awaiting
            || $order->paymentProofs->isNotEmpty()
        ) {
            return;
        }

        $order->update([
            'status'       => OrderStatus::Cancelled->value,
            'cancelled_at' => now(),
        ]);

        try {
            Mail::to($order->customer_email)->send(new OrderCancelled($order));
        } catch (\Throwable $e) {
            Log::error('OrderCancelled mail failed', [
                'order' => $this->orderId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
