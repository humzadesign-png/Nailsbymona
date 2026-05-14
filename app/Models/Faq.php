<?php

namespace App\Models;

use App\Enums\FaqCategory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasUlids;

    protected $fillable = ['category', 'question', 'answer', 'sort_order', 'is_active'];

    protected $casts = [
        'category'  => FaqCategory::class,
        'is_active' => 'boolean',
        'sort_order'=> 'integer',
    ];
}
