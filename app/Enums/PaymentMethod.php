<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case JazzCash     = 'jazzcash';
    case EasyPaisa    = 'easypaisa';
    case BankTransfer = 'bank_transfer';
    case Card         = 'card'; // reserved — Phase 6 SafePay

    public function label(): string
    {
        return match($this) {
            self::JazzCash     => 'JazzCash',
            self::EasyPaisa    => 'EasyPaisa',
            self::BankTransfer => 'Bank Transfer',
            self::Card         => 'Card',
        };
    }
}
