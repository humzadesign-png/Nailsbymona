<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Awaiting       = 'awaiting';
    case Verifying      = 'verifying';
    case Paid           = 'paid';
    case PartialAdvance = 'partial_advance';
    case Refunded       = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::Awaiting       => 'Awaiting Payment',
            self::Verifying      => 'Verifying',
            self::Paid           => 'Paid',
            self::PartialAdvance => 'Advance Paid',
            self::Refunded       => 'Refunded',
        };
    }
}
