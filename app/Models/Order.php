<?php

namespace App\Models;

use App\Enums\CourierType;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SizingCaptureMethod;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'order_number', 'customer_id',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'city', 'postal_code', 'notes',
        'subtotal_pkr', 'reorder_discount_pkr', 'shipping_pkr', 'total_pkr', 'advance_paid_pkr',
        'requires_advance', 'is_returning_customer',
        'payment_method', 'payment_status', 'status', 'sizing_capture_method',
        'tracking_number', 'courier',
        'confirmed_at', 'production_started_at', 'shipped_at', 'delivered_at', 'cancelled_at',
    ];

    protected $casts = [
        'status'                => OrderStatus::class,
        'payment_method'        => PaymentMethod::class,
        'payment_status'        => PaymentStatus::class,
        'sizing_capture_method' => SizingCaptureMethod::class,
        'courier'               => CourierType::class,
        'requires_advance'      => 'boolean',
        'is_returning_customer' => 'boolean',
        'confirmed_at'          => 'datetime',
        'production_started_at' => 'datetime',
        'shipped_at'            => 'datetime',
        'delivered_at'          => 'datetime',
        'cancelled_at'          => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sizingPhotos(): HasMany
    {
        return $this->hasMany(OrderSizingPhoto::class);
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(OrderPaymentProof::class);
    }

    /**
     * Generate the next sequential order number for the current year.
     *
     * Two concurrent orders could otherwise read the same "latest" row
     * and compute the same sequence — the second insert would then fail
     * on the unique constraint. To prevent that:
     *
     *   1. Read the latest row inside a transaction with lockForUpdate(),
     *      so a second concurrent reader blocks until we've committed.
     *   2. If a unique-constraint violation slips through anyway (e.g. the
     *      table is empty for the year and two readers see no row at all
     *      so no row gets locked), retry up to 3 times.
     */
    public static function generateOrderNumber(): string
    {
        $maxAttempts = 3;
        $lastError   = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $year = now()->year;

            $candidate = DB::transaction(function () use ($year) {
                $latest = static::query()
                    ->where('order_number', 'like', "NBM-{$year}-%")
                    ->orderByDesc('order_number')
                    ->lockForUpdate()
                    ->value('order_number');

                $seq = $latest ? ((int) substr($latest, -4)) + 1 : 1;

                return sprintf('NBM-%d-%04d', $year, $seq);
            });

            // Did anyone race us between our read and the eventual insert?
            // If the number already exists, retry.
            if (! static::where('order_number', $candidate)->exists()) {
                return $candidate;
            }
        }

        // Highly unlikely — fall back to a timestamp suffix so the order
        // can still be placed and Mona can renumber by hand if needed.
        return sprintf('NBM-%d-%04d', now()->year, (int) substr((string) now()->timestamp, -4));
    }

    /**
     * The advance amount required for this order, in PKR.
     *
     * - Bridal Trio: settings-driven deposit % (default 100 — full advance, per CLAUDE.md §7).
     * - Other orders ≥ the advance threshold: settings-driven advance % (default 25).
     * - Otherwise: full total.
     */
    public function advanceAmountPkr(): int
    {
        $settings = app(\App\Settings\StoreSettings::class);

        if ($this->items->contains(fn ($i) => $i->product_tier_snapshot === 'bridal_trio')) {
            $pct = max(0, min(100, $settings->bridal_deposit_percent));
            return (int) round($this->total_pkr * ($pct / 100));
        }

        if ($this->requires_advance) {
            $pct = max(0, min(100, $settings->advance_percent));
            return (int) round($this->total_pkr * ($pct / 100));
        }

        return $this->total_pkr;
    }

    /** Whether this order is awaiting proof upload and beyond deadline. */
    public function isWithinRefitWindow(): bool
    {
        return $this->status === OrderStatus::Delivered
            && $this->delivered_at
            && $this->delivered_at->diffInDays(now()) <= 7;
    }

    /**
     * Hours since the order was placed — for SLA tracking on awaiting-payment orders.
     * Null when the payment is no longer pending.
     */
    public function getPaymentAgeHoursAttribute(): ?int
    {
        if ($this->payment_status !== PaymentStatus::Awaiting || ! $this->created_at) {
            return null;
        }
        return (int) $this->created_at->diffInHours(now());
    }

    /**
     * Compact "🟢 2h" / "🟡 14h" / "🔴 1d 4h" label for the orders table.
     * Color thresholds: green < 12h, amber 12-24h, red > 24h (Mona's 24h SLA).
     */
    public function getPaymentAgeLabelAttribute(): ?string
    {
        $hours = $this->payment_age_hours;
        if ($hours === null) {
            return null;
        }

        $emoji = $hours < 12 ? '🟢' : ($hours < 24 ? '🟡' : '🔴');

        if ($hours < 24) {
            $time = "{$hours}h";
        } else {
            $days = intdiv($hours, 24);
            $rem  = $hours % 24;
            $time = $rem > 0 ? "{$days}d {$rem}h" : "{$days}d";
        }

        return "{$emoji} awaiting payment · {$time}";
    }

    /** Courier tracking URL from config. */
    public function courierTrackingUrl(): ?string
    {
        if (! $this->courier || ! $this->tracking_number) {
            return null;
        }
        $template = config('couriers.' . $this->courier->value);
        return $template ? str_replace('{tracking_number}', $this->tracking_number, $template) : null;
    }
}
