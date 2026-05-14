<?php

namespace App\Models;

use App\Enums\BlogCategory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogPost extends Model
{
    use HasUlids;

    protected $fillable = [
        'slug', 'title', 'excerpt', 'content', 'cover_image', 'cover_image_alt',
        'category', 'tags', 'meta_title', 'meta_description', 'og_image',
        'target_keyword', 'is_published', 'published_at', 'author_id', 'view_count',
    ];

    protected $casts = [
        'category'     => BlogCategory::class,
        'tags'         => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'view_count'   => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'blog_post_products');
    }
}
