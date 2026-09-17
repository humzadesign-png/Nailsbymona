<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'whatsapp', 'instagram',
        'default_shipping_address', 'city', 'postal_code',
        'has_sizing_on_file', 'notes',
        'total_orders', 'lifetime_value_pkr', 'last_ordered_at',
    ];

    protected $casts = [
        'has_sizing_on_file' => 'boolean',
        'last_ordered_at'    => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /** Custom design links (Instagram / WhatsApp quotes) created for this customer. */
    public function customOrderRequests(): HasMany
    {
        return $this->hasMany(CustomOrderRequest::class)->latest();
    }

    public function sizingProfile(): HasOne
    {
        return $this->hasOne(CustomerSizingProfile::class)->latestOfMany();
    }

    /**
     * All sizing photos this customer has uploaded across every order.
     * Source: order_sizing_photos (per-order uploads). The aspirational
     * customer_sizing_photos table isn't wired up yet — this accessor lets
     * us surface the photos on the Customer view today; once dedup logic
     * lands the implementation switches without breaking callers.
     */
    public function sizingPhotosFromOrders(): \Illuminate\Database\Eloquent\Collection
    {
        return OrderSizingPhoto::query()
            ->whereIn('order_id', $this->orders()->pluck('id'))
            ->orderByDesc('uploaded_at')
            ->get();
    }

    /**
     * The reorder discount is for customers who have actually bought before:
     * at least one order whose payment was confirmed (Confirmed or later).
     * Cancelled and still-unpaid orders don't count, so if a first order is
     * cancelled the next one is treated as a first order again.
     */
    public function qualifiesForReorderDiscount(): bool
    {
        return $this->orders()
            ->whereIn('status', [
                \App\Enums\OrderStatus::Confirmed,
                \App\Enums\OrderStatus::InProduction,
                \App\Enums\OrderStatus::Shipped,
                \App\Enums\OrderStatus::Delivered,
            ])
            ->exists();
    }

    /**
     * Normalize a Pakistani-style phone number to its last 10 significant
     * digits (the unique identifier of the line) so that all of these match:
     *
     *   +92 300 1234567   → 3001234567
     *   923001234567      → 3001234567
     *   03001234567       → 3001234567
     *   0300-1234567      → 3001234567
     *   (0300) 1234567    → 3001234567
     *
     * Returns '' for inputs that don't yield at least 7 useful digits.
     */
    public static function normalizePhoneTail(?string $raw): string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw) ?? '';
        if (strlen($digits) < 7) {
            return '';
        }
        // Strip the canonical PK prefixes (`92` country code or leading `0`).
        if (str_starts_with($digits, '92')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = ltrim($digits, '0');
        }
        // Keep only the last 10 digits — the unique mobile-line identifier
        // regardless of prefix form.
        return substr($digits, -10);
    }

    /**
     * Look up a customer by email (case-insensitive) or phone (Pakistani
     * tail-normalized so all common prefix forms match).
     *
     * Pulls candidate rows via an indexable LIKE prefilter (last 6 digits as
     * a substring), then does the precise final match in PHP. Inexpensive
     * at MVP volume; if Mona's customer list grows past ~10k, add a stored
     * `phone_digits` column with an index instead.
     */
    public static function findByContact(string $contact): ?self
    {
        $raw = trim($contact);
        if ($raw === '') {
            return null;
        }

        // Email branch — exact case-insensitive.
        if (str_contains($raw, '@')) {
            return static::whereRaw('LOWER(email) = ?', [strtolower($raw)])->first();
        }

        // Phone branch — tail normalization.
        $tail = self::normalizePhoneTail($raw);
        if ($tail === '') {
            return null;
        }

        // Coarse SQL prefilter: any phone/whatsapp containing the last 6
        // digits anywhere. Tight enough to skip most rows; sloppy enough
        // to handle prefix variation.
        $needle = '%' . substr($tail, -6) . '%';

        $candidates = static::query()
            ->where(function ($q) use ($needle) {
                $q->where('phone',    'like', $needle)
                  ->orWhere('whatsapp', 'like', $needle);
            })
            ->get();

        return $candidates->first(function ($cust) use ($tail) {
            return self::normalizePhoneTail($cust->phone)    === $tail
                || self::normalizePhoneTail($cust->whatsapp) === $tail;
        });
    }
}
