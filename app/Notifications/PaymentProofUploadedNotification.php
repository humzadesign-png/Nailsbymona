<?php

namespace App\Notifications;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * Sent to every admin when a customer uploads a payment proof on
 * /order/confirm. Two channels:
 *   • WebPush  — phone/desktop push via the admin PWA service worker
 *   • database — the bell in the Filament top bar, so it's there even if
 *                push is blocked or the phone was off
 */
class PaymentProofUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return (new WebPushMessage())
            ->title('Payment proof uploaded — ' . $this->order->order_number)
            ->body('Rs. ' . number_format($this->order->total_pkr) . ' · ' . $this->order->customer_name . ' · tap to review')
            ->icon('/icon-192.png')
            ->tag('proof-' . $this->order->id)
            ->data(['url' => $this->url()]);
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Payment proof uploaded — ' . $this->order->order_number)
            ->body('Rs. ' . number_format($this->order->total_pkr) . ' · ' . $this->order->customer_name . '. Check it and confirm the payment.')
            ->icon('heroicon-o-banknotes')
            ->iconColor('success')
            ->actions([
                Action::make('review')
                    ->label('Review order')
                    ->url($this->url())
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }

    private function url(): string
    {
        return OrderResource::getUrl('view', ['record' => $this->order], panel: 'admin');
    }
}
