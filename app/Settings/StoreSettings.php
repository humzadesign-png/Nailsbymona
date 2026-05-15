<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class StoreSettings extends Settings
{
    // ── Contact ───────────────────────────────────────────────────────────────
    public string $whatsapp_number   = '+923000000000';
    public string $instagram_handle  = 'nailsbymona';
    public string $tiktok_handle     = 'nailsbymona';
    public string $contact_email     = 'hello@nailsbymona.com';
    public string $business_hours    = 'Mon–Sat, 10am–7pm (PKT)';

    // ── Payments (manual) ─────────────────────────────────────────────────────
    public string $jazzcash_number   = '';
    public string $jazzcash_name     = '';
    public string $easypaisa_number  = '';
    public string $easypaisa_name    = '';
    public string $bank_name         = '';
    public string $bank_account_name = '';
    public string $bank_account_no   = '';
    public string $bank_iban         = '';

    // ── Shipping ──────────────────────────────────────────────────────────────
    public int    $shipping_flat_pkr    = 350;   // standard nationwide rate
    public int    $shipping_free_above  = 5000;  // free shipping threshold (0 = disabled)

    // ── Order rules ───────────────────────────────────────────────────────────
    public int    $advance_threshold_pkr = 5000; // orders ≥ this require 20-30% advance
    public int    $advance_percent       = 25;   // advance percentage

    public static function group(): string
    {
        return 'store';
    }
}
