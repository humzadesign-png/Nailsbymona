<?php

namespace App\Enums;

enum CustomOrderStatus: string
{
    case Pending   = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Link sent — waiting',
            self::Completed => 'Order placed',
            self::Cancelled => 'Cancelled',
        };
    }
}
