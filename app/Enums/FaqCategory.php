<?php

namespace App\Enums;

enum FaqCategory: string
{
    case Sizing      = 'sizing';
    case Payment     = 'payment';
    case Shipping    = 'shipping';
    case Returns     = 'returns';
    case Application = 'application';
    case Bridal      = 'bridal';
    case General     = 'general';

    public function label(): string
    {
        return match($this) {
            self::Sizing      => 'Sizing',
            self::Payment     => 'Payment',
            self::Shipping    => 'Shipping',
            self::Returns     => 'Returns & Refits',
            self::Application => 'Application',
            self::Bridal      => 'Bridal',
            self::General     => 'General',
        };
    }
}
