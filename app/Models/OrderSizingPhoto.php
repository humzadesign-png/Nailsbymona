<?php

namespace App\Models;

use App\Enums\PhotoType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderSizingPhoto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'path', 'photo_type', 'mime_type', 'file_size', 'uploaded_at',
    ];

    protected $casts = [
        'photo_type'  => PhotoType::class,
        'uploaded_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** Admin-only signed URL — files live on the private disk. */
    public function getViewerUrlAttribute(): ?string
    {
        if (! $this->path) {
            return null;
        }
        return route('admin.private-file', [
            'category' => 'sizing',
            'order'    => $this->order_id,
            'filename' => basename($this->path),
        ]);
    }
}
