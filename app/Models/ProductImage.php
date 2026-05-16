<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasUlids;

    protected $fillable = ['product_id', 'path', 'alt', 'sort_order'];

    protected $casts = ['sort_order' => 'integer'];

    protected function sortOrder(): Attribute
    {
        return Attribute::make(
            set: fn (?int $value) => $value ?? 0,
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
