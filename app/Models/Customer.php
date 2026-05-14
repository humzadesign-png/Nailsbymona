<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'whatsapp',
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

    public function sizingProfile(): HasOne
    {
        return $this->hasOne(CustomerSizingProfile::class)->latestOfMany();
    }

    /** Look up a customer by phone or email (for returning-customer check). */
    public static function findByContact(string $contact): ?self
    {
        return static::where('email', $contact)
            ->orWhere('phone', $contact)
            ->orWhere('whatsapp', $contact)
            ->first();
    }
}
