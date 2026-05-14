<?php

/**
 * Nails by Mona — site-wide settings.
 *
 * These are PLACEHOLDER values for development.
 * In production, all sensitive values (phone numbers, IBANs) will
 * be stored in the database via spatie/laravel-settings and
 * editable through the Filament admin panel (Phase 3).
 *
 * For Phase 2 development, these defaults allow the order flow
 * pages to render without Filament being set up.
 */
return [

    // ── Contact ───────────────────────────────────────────────────────────────
    'whatsapp_number'  => env('NBM_WHATSAPP', '92XXXXXXXXXX'),
    'contact_email'    => env('NBM_EMAIL', 'hello@nailsbymona.pk'),
    'business_hours'   => 'Mon – Sat, 10am – 9pm PKT',

    // ── JazzCash ──────────────────────────────────────────────────────────────
    'jazzcash_number'  => env('NBM_JAZZCASH_NUMBER', '03XX-XXXXXXX'),
    'jazzcash_name'    => env('NBM_JAZZCASH_NAME', 'Mona [Surname]'),

    // ── EasyPaisa ─────────────────────────────────────────────────────────────
    'easypaisa_number' => env('NBM_EASYPAISA_NUMBER', '03XX-XXXXXXX'),
    'easypaisa_name'   => env('NBM_EASYPAISA_NAME', 'Mona [Surname]'),

    // ── Bank Transfer ─────────────────────────────────────────────────────────
    'bank_name'        => env('NBM_BANK_NAME', '[Bank name]'),
    'bank_account_name'=> env('NBM_BANK_ACCOUNT_NAME', 'Mona [Surname]'),
    'bank_iban'        => env('NBM_BANK_IBAN', 'PK00XXXX0000000000000000'),

    // ── Shipping ──────────────────────────────────────────────────────────────
    'shipping_flat_pkr'=> (int) env('NBM_SHIPPING_PKR', 300),

    // ── Order rules ───────────────────────────────────────────────────────────
    'advance_threshold_pkr' => 5000,  // orders ≥ this require a 30% advance
    'advance_rate'          => 0.30,  // 30% advance for standard large orders
    'bridal_deposit_rate'   => 0.50,  // 50% deposit for Bridal Trio

    // ── Lead times (working days) ─────────────────────────────────────────────
    'lead_time_standard' => 7,
    'lead_time_bridal'   => 12,

];
