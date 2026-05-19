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

        // We previously short-circuited when ANY proof row existed, but a
        // customer can upload an empty/wrong screenshot and indefinitely
        // block the auto-cancel safety net. The order would silently rot
        // in Awaiting forever.
        //
        // New rule: cancel if payment_status is still Awaiting or Verifying
        // AND no proof has been verified by an admin. Mona's confirm action
        // stamps OrderPaymentProof.verified_at, so verifying anything keeps
        // the order safe.
        if (! $order) {
            return;
        }

        $statusOk = $order->payment_status === PaymentStatus::Awaiting
                 || $order->payment_status === PaymentStatus::Verifying;
        if (! $statusOk) {
            return; // Already Paid / PartialAdvance / Refunded — leave alone.
        }

        $hasVerifiedProof = $order->paymentProofs->contains(fn ($p) => $p->verified_at !== null);
        if ($hasVerifiedProof) {
            return; // Admin-verified — don't cancel.
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
