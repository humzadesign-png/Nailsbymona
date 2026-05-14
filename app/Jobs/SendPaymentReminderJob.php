<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Mail\PaymentReminder;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        private readonly string $orderId,
        private readonly int    $reminderNumber,
    ) {}

    public function handle(): void
    {
        $order = Order::with('items')->find($this->orderId);

        // Only send if the order still hasn't been paid
        if (! $order || $order->payment_status !== PaymentStatus::Awaiting) {
            return;
        }

        try {
            Mail::to($order->customer_email)
                ->send(new PaymentReminder($order, $this->reminderNumber));
        } catch (\Throwable $e) {
            Log::error('PaymentReminder mail failed', [
                'order'  => $this->orderId,
                'num'    => $this->reminderNumber,
                'error'  => $e->getMessage(),
            ]);
        }
    }
}
