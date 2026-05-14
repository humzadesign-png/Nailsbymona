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

    /** Generate the next sequential order number for the current year. */
    public static function generateOrderNumber(): string
    {
        $year = now()->year;
        $latest = static::where('order_number', 'like', "NBM-{$year}-%")
            ->orderByDesc('order_number')
            ->value('order_number');

        $seq = $latest ? ((int) substr($latest, -4)) + 1 : 1;

        return sprintf('NBM-%d-%04d', $year, $seq);
    }

    /** The advance amount required for this order. */
    public function advanceAmountPkr(): int
    {
        if ($this->items->contains(fn ($i) => $i->product_tier_snapshot === 'bridal_trio')) {
            return (int) round($this->total_pkr * 0.50);
        }
        if ($this->requires_advance) {
            return (int) round($this->total_pkr * 0.30);
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
