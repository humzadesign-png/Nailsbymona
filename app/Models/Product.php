<?php

namespace App\Models;

use App\Enums\ProductTier;
use App\Enums\StockStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasUlids;

    protected $fillable = [
        'slug', 'name', 'description', 'tier', 'price_pkr',
        'cover_image', 'is_active', 'is_featured', 'stock_status',
        'lead_time_days', 'meta_title', 'meta_description', 'sort_order',
    ];

    protected $casts = [
        'tier'         => ProductTier::class,
        'stock_status' => StockStatus::class,
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'price_pkr'    => 'integer',
        'sort_order'   => 'integer',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function blogPosts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_products');
    }

    public function ugcPhotos(): HasMany
    {
        return $this->hasMany(UgcPhoto::class);
    }

    public function formattedPrice(): string
    {
        return 'Rs. ' . number_format($this->price_pkr);
    }
}
