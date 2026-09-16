<?php

namespace App\Models;

use App\Enums\CustomOrderStatus;
use App\Settings\StoreSettings;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * A custom design agreed with a customer outside the shop (Instagram / WhatsApp DMs).
 * The customer completes it through a private link: /custom/{token}.
 */
class CustomOrderRequest extends Model
{
    use HasUlids;

    /** Default link lifetime when Mona creates a request. */
    public const DEFAULT_EXPIRY_DAYS = 7;

    protected $fillable = [
        'token',
        'customer_name', 'customer_phone', 'customer_instagram', 'customer_email',
        'design_title', 'design_description', 'reference_images',
        'price_pkr', 'shipping_pkr', 'lead_time_days',
        'status', 'expires_at', 'opened_at', 'order_id',
        'admin_notes',
    ];

    protected $casts = [
        'reference_images' => 'array',
        'price_pkr'        => 'integer',
        'shipping_pkr'     => 'integer',
        'lead_time_days'   => 'integer',
        'status'           => CustomOrderStatus::class,
        'expires_at'       => 'datetime',
        'opened_at'        => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $request) {
            // 40 random chars — unguessable, safe to send over WhatsApp.
            $request->token      ??= Str::random(40);
            $request->status     ??= CustomOrderStatus::Pending;
            $request->expires_at ??= now()->addDays(self::DEFAULT_EXPIRY_DAYS)->endOfDay();
        });

        // The admin picks a date — keep the link valid through the end of that day.
        static::saving(function (self $request) {
            if ($request->isDirty('customer_instagram')) {
                $request->customer_instagram = self::normalizeInstagram($request->customer_instagram);
            }
            if ($request->expires_at && $request->isDirty('expires_at')) {
                $request->expires_at = $request->expires_at->copy()->endOfDay();
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /** True when the customer can still use the link to place the order. */
    public function isUsable(): bool
    {
        return $this->status === CustomOrderStatus::Pending && ! $this->isExpired();
    }

    public function publicUrl(): string
    {
        return route('custom-order.show', $this->token);
    }

    /** Reference image URLs (public disk) for the customer page. */
    public function referenceImageUrls(): array
    {
        return collect($this->reference_images ?? [])
            ->filter()
            ->map(fn ($path) => Storage::disk('public')->url($path))
            ->values()
            ->all();
    }

    /** The message Mona sends with the link — same text for WhatsApp and Instagram. */
    public function shareMessage(): string
    {
        $firstName = Str::of($this->customer_name)->explode(' ')->first();

        return "Hello {$firstName}, this is Nails by Mona 💜\n\n"
             . "Your custom design \"{$this->design_title}\" is ready to order. "
             . "Please open this link to take your nail sizing photos (our camera guide shows you exactly how) and complete your payment:\n\n"
             . $this->publicUrl() . "\n\n"
             . "The link is valid until " . $this->expires_at?->format('j M') . ".";
    }

    /** wa.me link with the message pre-filled, or null when there's no WhatsApp number. */
    public function whatsappUrl(): ?string
    {
        $digits = preg_replace('/\D+/', '', $this->customer_phone ?? '');
        if (strlen($digits) < 7) {
            return null;
        }
        // Pakistani local format 03xx… → 923xx… so wa.me resolves the number.
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);            // 0092… international prefix
        } elseif (str_starts_with($digits, '0')) {
            $digits = '92' . substr($digits, 1);     // 03xx… local format
        } elseif (strlen($digits) === 10 && str_starts_with($digits, '3')) {
            $digits = '92' . $digits;                // 3xx… without prefix
        }

        return "https://wa.me/{$digits}?text=" . rawurlencode($this->shareMessage());
    }

    /** Opens an Instagram DM with the customer (Instagram doesn't support pre-filled text). */
    public function instagramUrl(): ?string
    {
        $handle = self::normalizeInstagram($this->customer_instagram);

        return $handle ? "https://ig.me/m/{$handle}" : null;
    }

    /** "@Sana.Nails " or "instagram.com/sana.nails/" → "sana.nails". */
    public static function normalizeInstagram(?string $raw): ?string
    {
        $handle = trim((string) $raw);
        $handle = preg_replace('#^(https?://)?(www\.)?instagram\.com/#i', '', $handle);
        $handle = trim($handle, "@/ \t");

        return preg_match('/^[A-Za-z0-9._]{1,30}$/', $handle) ? strtolower($handle) : null;
    }

    /**
     * Shipping for this quote: Mona's per-request override if she set one,
     * otherwise the store's standard flat-rate / free-above rules.
     */
    public function shippingPkr(): int
    {
        if ($this->shipping_pkr !== null) {
            return max(0, (int) $this->shipping_pkr);
        }

        $settings  = app(StoreSettings::class);
        $freeAbove = (int) $settings->shipping_free_above;

        return ($freeAbove > 0 && $this->price_pkr >= $freeAbove)
            ? 0
            : max(0, (int) $settings->shipping_flat_pkr);
    }

    /** The single line item this request contributes to the checkout bag. */
    public function toBagItem(): array
    {
        return [
            'slug'      => 'custom-' . strtolower(substr($this->id, -8)),
            'name'      => 'Custom design — ' . $this->design_title,
            'tier'      => 'custom',
            'price_pkr' => (int) $this->price_pkr,
            'qty'       => 1,
            'image'     => $this->referenceImageUrls()[0] ?? null,
        ];
    }
}
