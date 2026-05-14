<?php

namespace App\Enums;

enum UgcPlacement: string
{
    case HomeGrid         = 'home_grid';
    case ProductCarousel  = 'product_carousel';
    case BridalGallery    = 'bridal_gallery';
    case AboutInline      = 'about_inline';

    public function label(): string
    {
        return match($this) {
            self::HomeGrid        => 'Home — Worn across Pakistan',
            self::ProductCarousel => 'Product page carousel',
            self::BridalGallery   => 'Bridal gallery',
            self::AboutInline     => 'About page',
        };
    }
}
