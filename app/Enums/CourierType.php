<?php

namespace App\Enums;

enum CourierType: string
{
    case Tcs      = 'tcs';
    case Leopards = 'leopards';
    case Mp       = 'mp';
    case Blueex   = 'blueex';

    public function label(): string
    {
        return match($this) {
            self::Tcs      => 'TCS',
            self::Leopards => 'Leopards Courier',
            self::Mp       => 'M&P',
            self::Blueex   => 'BlueEx',
        };
    }
}
