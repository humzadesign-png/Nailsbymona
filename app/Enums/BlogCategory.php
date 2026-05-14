<?php

namespace App\Enums;

enum BlogCategory: string
{
    case Bridal       = 'bridal';
    case Tutorials    = 'tutorials';
    case Trends       = 'trends';
    case Care         = 'care';
    case BehindScenes = 'behind_scenes';

    public function label(): string
    {
        return match($this) {
            self::Bridal       => 'Bridal',
            self::Tutorials    => 'Tutorials',
            self::Trends       => 'Trends',
            self::Care         => 'Care',
            self::BehindScenes => 'Behind the Scenes',
        };
    }
}
