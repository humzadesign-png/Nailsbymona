<?php

namespace App\Enums;

enum PhotoType: string
{
    case Fingers      = 'fingers';
    case Thumb        = 'thumb';
    case FingersOther = 'fingers_other';
    case ThumbOther   = 'thumb_other';

    public function label(): string
    {
        return match($this) {
            self::Fingers      => 'Fingers (main hand)',
            self::Thumb        => 'Thumb (main hand)',
            self::FingersOther => 'Fingers (other hand)',
            self::ThumbOther   => 'Thumb (other hand)',
        };
    }
}
