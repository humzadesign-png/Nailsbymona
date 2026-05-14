<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasUlids;

    protected $fillable = ['email', 'source', 'subscribed_at', 'unsubscribed_at'];

    protected $casts = [
        'subscribed_at'   => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
}
