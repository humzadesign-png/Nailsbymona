<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPaymentProof extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'path', 'mime_type', 'file_size', 'is_advance', 'uploaded_at', 'verified_at',
    ];

    protected $casts = [
        'is_advance'  => 'boolean',
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
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
            'category' => 'payment-proofs',
            'order'    => $this->order_id,
            'filename' => basename($this->path),
        ]);
    }
}
