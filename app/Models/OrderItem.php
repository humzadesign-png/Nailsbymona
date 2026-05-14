<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id',
        'product_name_snapshot', 'product_tier_snapshot', 'product_slug_snapshot',
        'unit_price_pkr', 'qty', 'sizing_notes',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function lineTotalPkr(): Attribute
    {
        return Attribute::get(fn () => $this->unit_price_pkr * $this->qty);
    }
}
