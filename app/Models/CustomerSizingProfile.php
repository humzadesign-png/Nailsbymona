<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerSizingProfile extends Model
{
    protected $fillable = [
        'customer_id',
        'notes',
        'verified_by_admin_at',
        'source_order_id',
        // Right hand
        'size_r_thumb', 'size_r_index', 'size_r_middle', 'size_r_ring', 'size_r_pinky',
        // Left hand
        'size_l_thumb', 'size_l_index', 'size_l_middle', 'size_l_ring', 'size_l_pinky',
    ];

    protected $casts = [
        'verified_by_admin_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sourceOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'source_order_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(CustomerSizingPhoto::class, 'customer_sizing_profile_id');
    }

    /** Returns true if at least one size has been recorded. */
    public function hasSizes(): bool
    {
        return collect([
            $this->size_r_thumb, $this->size_r_index, $this->size_r_middle,
            $this->size_r_ring,  $this->size_r_pinky,
            $this->size_l_thumb, $this->size_l_index, $this->size_l_middle,
            $this->size_l_ring,  $this->size_l_pinky,
        ])->filter()->isNotEmpty();
    }
}
