<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Order $order) {}

    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return (new WebPushMessage())
            ->title('New order — ' . $this->order->order_number)
            ->body('Rs. ' . number_format($this->order->total_pkr) . ' · ' . $this->order->customer_name)
            ->icon('/icon-192.png')
            ->data(['url' => '/admin/orders/' . $this->order->id . '/edit']);
    }
}
