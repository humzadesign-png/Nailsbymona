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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasUuids, SoftDeletes;

    /**
     * Customer-stat rollback rules:
     *
     *   • When an order is deleted, decrement `total_orders` and
     *     `lifetime_value_pkr` on the linked customer.
     *   • When an order transitions to Cancelled (from any non-cancelled
     *     status), do the same — once per transition.
     *
     * Counters are clamped at zero so a re-cancel or stat drift never
     * leaves a negative value visible in the admin panel.
     */
    protected static function booted(): void
    {
        static::updating(function (self $order) {
            if (! $order->isDirty('status') || ! $order->customer_id) {
                return;
            }
            $newStatus = $order->status instanceof OrderStatus
                ? $order->status
                : OrderStatus::tryFrom((string) $order->status);
            $oldRaw    = $order->getOriginal('status');
            $oldStatus = $oldRaw instanceof OrderStatus
                ? $oldRaw
                : OrderStatus::tryFrom((string) $oldRaw);

            if ($newStatus === OrderStatus::Cancelled
                && $oldStatus !== OrderStatus::Cancelled) {
                self::rollbackCustomerStats($order);
            }
        });

        static::deleting(function (self $order) {
            // Skip if this row was already cancelled — the cancel hook
            // already decremented and we don't want to double-count.
            if ($order->status === OrderStatus::Cancelled || ! $order->customer_id) {
                return;
            }
            self::rollbackCustomerStats($order);
        });
    }

    /** Decrement the linked customer's running totals by this order's contribution. */
    private static function rollbackCustomerStats(self $order): void
    {
        $customer = Customer::find($order->customer_id);
        if (! $customer) {
            return;
        }
        $customer->forceFill([
            'total_orders'       => max(0, (int) $customer->total_orders - 1),
            'lifetime_value_pkr' => max(0, (int) $customer->lifetime_value_pkr - (int) $order->total_pkr),
        ])->save();
    }

    protected $fillable = [
        'order_number', 'customer_id',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'city', 'postal_code', 'notes',
        'subtotal_pkr', 'reorder_discount_pkr', 'shipping_pkr', 'total_pkr', 'advance_paid_pkr',
        'requires_advance', 'is_returning_customer', 'is_custom',
        'payment_method', 'payment_status', 'status', 'sizing_capture_method',
        'tracking_number', 'courier',
        'confirmed_at', 'production_started_at', 'shipped_at', 'delivered_at', 'cancelled_at',
        'estimated_dispatch_at',
        'refit_requested_at', 'refit_shipped_at', 'refit_notes',
    ];

    protected $casts = [
        'status'                => OrderStatus::class,
        'payment_method'        => PaymentMethod::class,
        'payment_status'        => PaymentStatus::class,
        'sizing_capture_method' => SizingCaptureMethod::class,
        'courier'               => CourierType::class,
        'requires_advance'      => 'boolean',
        'is_returning_customer' => 'boolean',
        'is_custom'             => 'boolean',
        'confirmed_at'          => 'datetime',
        'production_started_at' => 'datetime',
        'shipped_at'            => 'datetime',
        'delivered_at'          => 'datetime',
        'cancelled_at'          => 'datetime',
        'estimated_dispatch_at' => 'datetime',
        'refit_requested_at'    => 'datetime',
        'refit_shipped_at'      => 'datetime',
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

    /** The custom design request this order came from (custom orders only). */
    public function customOrderRequest(): HasOne
    {
        return $this->hasOne(CustomOrderRequest::class);
    }

    /**
     * Orders still waiting on payment (Awaiting or Verifying) that are NOT
     * cancelled. Cancelled orders keep their stale payment_status, so every
     * "awaiting payment" queue must go through this scope.
     */
    public function scopeAwaitingPayment($query)
    {
        return $query
            ->whereIn('payment_status', [PaymentStatus::Awaiting, PaymentStatus::Verifying])
            ->where('status', '!=', OrderStatus::Cancelled);
    }

    /**
     * Generate the next sequential order number for the current year.
     *
     * IMPORTANT — must use withTrashed() everywhere. The orders table has
     * a UNIQUE constraint on `order_number` that ignores deleted_at, but
     * Eloquent's default query scope filters soft-deleted rows out. If a
     * soft-deleted order with the candidate number exists, the default
     * Eloquent check returns "free" but the INSERT immediately fails with
     * a duplicate-key violation. Block 5 added SoftDeletes to Order and
     * exposed this race (a soft-deleted NBM-2026-0001 in prod was blocking
     * every new order). The fix is to count soft-deleted rows as taken.
     *
     * Race conditions to defend against:
     *
     *   • Two concurrent placements read the same "latest" row and compute
     *     the same sequence. lockForUpdate() inside a transaction protects
     *     this — the second reader blocks until the first commits.
     *
     *   • Empty-year case (e.g. first order on Jan 1). lockForUpdate() locks
     *     the rows it reads; with zero rows, there's nothing to lock, and
     *     two concurrent first-of-year placements would both compute "0001".
     *     The unique constraint on order_number prevents both inserts
     *     succeeding — only one wins. The loser retries.
     *
     *   • Soft-deleted row collision: any number ever issued — even to a
     *     row that was later deleted — stays reserved (the DB's unique
     *     index ignores soft-delete state). withTrashed() makes this
     *     visible to the generator so we always skip past taken numbers.
     *
     * Retry budget: 5 attempts with jittered backoff. After that, fall back
     * to a timestamp-suffixed number so the order still places (Mona can
     * spot the irregular number and re-issue if she wants).
     */
    public static function generateOrderNumber(): string
    {
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $year = now()->year;

            $candidate = DB::transaction(function () use ($year) {
                $latest = static::query()
                    ->withTrashed() // include soft-deleted rows in the sequence
                    ->where('order_number', 'like', "NBM-{$year}-%")
                    ->orderByDesc('order_number')
                    ->lockForUpdate()
                    ->value('order_number');

                $seq = $latest ? ((int) substr($latest, -4)) + 1 : 1;

                return sprintf('NBM-%d-%04d', $year, $seq);
            });

            // TOCTOU check: between our compute and the eventual INSERT, did
            // another writer claim this number? withTrashed() is mandatory
            // here for the same reason as the SELECT above.
            if (! static::withTrashed()->where('order_number', $candidate)->exists()) {
                return $candidate;
            }

            // Jittered backoff (10-50ms × attempt) to avoid two retriers
            // synchronizing forever.
            usleep(random_int(10_000, 50_000) * $attempt);
        }

        // Fallback: timestamp-suffixed number so the placement still
        // succeeds. Mona can re-issue a clean number in admin if she
        // catches it.
        return sprintf('NBM-%d-T%04d', now()->year, (int) substr((string) now()->timestamp, -4));
    }

    /**
     * Amount the customer must pay before production starts, in PKR.
     *
     * Since 2026-09-16 every order is paid in full up front, so this is the
     * order total. Orders placed earlier under the partial-advance rule keep
     * `requires_advance = true` and are still asked for the advance share
     * they were quoted (settings-driven %), so those customers aren't
     * suddenly shown a different amount.
     */
    public function advanceAmountPkr(): int
    {
        if (! $this->requires_advance || $this->isBridalTrio()) {
            return (int) $this->total_pkr;
        }

        $pct = max(0, min(100, (int) app(\App\Settings\StoreSettings::class)->advance_percent));

        return (int) round($this->total_pkr * ($pct / 100));
    }

    /** True only for older orders still on the advance-then-balance flow. */
    public function isLegacyAdvanceOrder(): bool
    {
        return $this->requires_advance && $this->advanceAmountPkr() < (int) $this->total_pkr;
    }

    /**
     * Whether this order contains a Bridal Trio line item.
     * Used to switch lead-time + deposit logic.
     *
     * Resilient to unloaded relations: prefers the in-memory collection if
     * `items` was eager-loaded, otherwise falls back to a single COUNT query.
     * Either way, no N+1.
     */
    public function isBridalTrio(): bool
    {
        if ($this->relationLoaded('items')) {
            return $this->items->contains(fn ($i) => $i->product_tier_snapshot === 'bridal_trio');
        }
        return $this->items()->where('product_tier_snapshot', 'bridal_trio')->exists();
    }

    /**
     * Lead time in calendar days for this order, pulled from StoreSettings.
     * Bridal Trio orders use the bridal lead time; everything else uses standard.
     */
    public function leadTimeDays(): int
    {
        // Custom designs can carry their own lead time, set when Mona quoted them.
        if ($this->is_custom && ($customDays = $this->customOrderRequest?->lead_time_days)) {
            return (int) $customDays;
        }

        $settings = app(\App\Settings\StoreSettings::class);
        return $this->isBridalTrio()
            ? (int) $settings->lead_time_bridal_days
            : (int) $settings->lead_time_standard_days;
    }

    /**
     * Date the customer should expect dispatch.
     *
     * Preference order:
     *  1. `estimated_dispatch_at` column (pinned at order placement) — stable across reloads.
     *  2. `created_at + leadTimeDays` (fallback for orders predating the column).
     *
     * Pass `$fromNow = true` to recompute from `now()` instead — used in the
     * payment-verified and in-production emails where the customer wants to
     * see a fresh estimate based on when production actually starts.
     */
    public function estimatedDispatchAt(bool $fromNow = false): \Illuminate\Support\Carbon
    {
        if ($fromNow) {
            return now()->addDays($this->leadTimeDays());
        }
        if ($this->estimated_dispatch_at) {
            return $this->estimated_dispatch_at;
        }
        $anchor = $this->created_at ?? now();
        return $anchor->copy()->addDays($this->leadTimeDays());
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
        if ($this->payment_status !== PaymentStatus::Awaiting
            || $this->status === OrderStatus::Cancelled
            || ! $this->created_at) {
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

    /**
     * wa.me link with a status-aware update for the customer, so Mona can
     * confirm payment / production / dispatch on WhatsApp instead of relying
     * on email alone. Null when there's no usable phone number.
     */
    public function whatsappUpdateUrl(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->customer_phone);
        if (strlen($digits) < 10) {
            return null;
        }
        // Checkout stores the local part after a fixed +92 prefix (3001234567);
        // older rows may be 03001234567 or 923001234567.
        if (str_starts_with($digits, '0')) {
            $digits = '92' . substr($digits, 1);
        } elseif (strlen($digits) === 10 && str_starts_with($digits, '3')) {
            $digits = '92' . $digits;
        }

        return "https://wa.me/{$digits}?text=" . rawurlencode($this->whatsappUpdateMessage());
    }

    public function whatsappUpdateMessage(): string
    {
        $first    = \Illuminate\Support\Str::of((string) $this->customer_name)->explode(' ')->first() ?: 'there';
        $num      = $this->order_number;
        $total    = 'Rs. ' . number_format((int) $this->total_pkr);
        $dispatch = $this->estimatedDispatchAt()->format('D, j M');
        $track    = route('track');
        $hello    = "Hello {$first}, this is Nails by Mona 💜\n\n";

        return match ($this->status) {
            OrderStatus::New => $this->payment_status === PaymentStatus::Verifying
                ? $hello . "Thank you for sending your payment for order {$num}. We're checking it now and will confirm shortly."
                : $hello . "Thank you for your order {$num}. To start making your set, please send {$total} and upload the payment screenshot on your order page. You can find your order here: {$track}",
            OrderStatus::Confirmed => $hello . "Your payment of " . 'Rs. ' . number_format((int) ($this->advance_paid_pkr ?: $this->total_pkr))
                . " for order {$num} is confirmed. We're starting on your set now — expected dispatch around {$dispatch}.\n\nTrack your order any time: {$track}",
            OrderStatus::InProduction => $hello . "Your set for order {$num} is now being made by hand. Expected dispatch around {$dispatch}.",
            OrderStatus::Shipped => $hello . "Your order {$num} is on its way"
                . ($this->courier ? " with {$this->courier->label()}" : '')
                . ($this->tracking_number ? ". Tracking number: {$this->tracking_number}" : '') . '.'
                . (($url = $this->courierTrackingUrl()) ? "\n\nTrack it here: {$url}" : ''),
            OrderStatus::Delivered => $hello . "Your order {$num} should have arrived — we hope you love it! If any nail doesn't sit right, message us within 7 days for your free first refit.",
            default => $hello . "About your order {$num} — ",
        };
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
