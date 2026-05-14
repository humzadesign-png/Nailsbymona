<?php

namespace App\Enums;

enum StockStatus: string
{
    case InStock      = 'in_stock';
    case MadeToOrder  = 'made_to_order';
    case SoldOut      = 'sold_out';

    public function label(): string
    {
        return match($this) {
            self::InStock     => 'In Stock',
            self::MadeToOrder => 'Made to Order',
            self::SoldOut     => 'Sold Out',
        };
    }
}
