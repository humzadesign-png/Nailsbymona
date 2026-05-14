<?php

namespace App\Models;

use App\Enums\UgcPlacement;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UgcPhoto extends Model
{
    use HasUlids;

    protected $fillable = [
        'image_path', 'alt', 'placement', 'product_id',
        'face_visible', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'placement'    => UgcPlacement::class,
        'face_visible' => 'boolean',
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
