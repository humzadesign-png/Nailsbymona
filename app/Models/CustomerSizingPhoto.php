<?php

namespace App\Models;

use App\Enums\PhotoType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerSizingPhoto extends Model
{
    protected $fillable = ['customer_sizing_profile_id', 'path', 'photo_type', 'uploaded_at'];

    protected $casts = [
        'photo_type'  => PhotoType::class,
        'uploaded_at' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(CustomerSizingProfile::class, 'customer_sizing_profile_id');
    }
}
