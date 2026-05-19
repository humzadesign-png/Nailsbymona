<?php

namespace App\Models;

use App\Enums\UgcPlacement;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Public-display safety net. Use this scope on every query that feeds
     * customer-facing UGC. It enforces CLAUDE.md §24's locked rule:
     * a photo flagged `face_visible = true` MUST NEVER appear publicly.
     *
     * Pattern:  UgcPhoto::published()->where('placement', ...)
     *
     * Filament admin queries should bypass this scope (the admin needs to
     * see and toggle face_visible photos to manage them).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where('face_visible', false);
    }
}
