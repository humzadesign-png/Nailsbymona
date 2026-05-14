<?php

namespace App\Enums;

enum ProductTier: string
{
    case Everyday     = 'everyday';
    case Signature    = 'signature';
    case Glam         = 'glam';
    case BridalSingle = 'bridal_single';
    case BridalTrio   = 'bridal_trio';

    public function label(): string
    {
        return match($this) {
            self::Everyday     => 'Everyday',
            self::Signature    => 'Signature',
            self::Glam         => 'Glam',
            self::BridalSingle => 'Bridal',
            self::BridalTrio   => 'Bridal Trio',
        };
    }

    public function isBridal(): bool
    {
        return in_array($this, [self::BridalSingle, self::BridalTrio]);
    }
}
