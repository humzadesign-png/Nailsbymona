<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case Materials     = 'materials';
    case GelPolish     = 'gel_polish';
    case Packaging     = 'packaging';
    case Courier       = 'courier';
    case Marketing     = 'marketing';
    case Tools         = 'tools';
    case Utilities     = 'utilities';
    case Other         = 'other';

    public function label(): string
    {
        return match($this) {
            self::Materials  => 'Materials & Supplies',
            self::GelPolish  => 'Gel Nail Polishes',
            self::Packaging  => 'Packaging',
            self::Courier    => 'Courier & Shipping',
            self::Marketing  => 'Marketing & Ads',
            self::Tools      => 'Tools & Equipment',
            self::Utilities  => 'Utilities & Overheads',
            self::Other      => 'Other',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Materials  => '#BFA4CE',
            self::GelPolish  => '#D4847A',
            self::Packaging  => '#B8924A',
            self::Courier    => '#5B8DB8',
            self::Marketing  => '#C96E6E',
            self::Tools      => '#6BAE91',
            self::Utilities  => '#A09080',
            self::Other      => '#C4B8D2',
        };
    }
}
