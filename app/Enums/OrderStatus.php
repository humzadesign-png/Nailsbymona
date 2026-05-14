<?php

namespace App\Enums;

enum OrderStatus: string
{
    case New          = 'new';
    case Confirmed    = 'confirmed';
    case InProduction = 'in_production';
    case Shipped      = 'shipped';
    case Delivered    = 'delivered';
    case Cancelled    = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::New          => 'Order Placed',
            self::Confirmed    => 'Payment Received',
            self::InProduction => 'In Production',
            self::Shipped      => 'Shipped',
            self::Delivered    => 'Delivered',
            self::Cancelled    => 'Cancelled',
        };
    }

    public function activeLabel(): string
    {
        return match($this) {
            self::New          => 'Your order has been placed — waiting for payment confirmation.',
            self::Confirmed    => 'Payment confirmed. Mona will start making your set soon.',
            self::InProduction => 'Mona is making your set.',
            self::Shipped      => 'Your order is on its way! See tracking details below.',
            self::Delivered    => 'Delivered. We hope you love them — enjoy wearing them!',
            self::Cancelled    => 'This order was cancelled.',
        };
    }
}
