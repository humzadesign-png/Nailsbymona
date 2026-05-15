<?php

namespace App\Models;

use App\Enums\ExpenseCategory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasUlids;

    protected $fillable = [
        'category',
        'description',
        'amount_pkr',
        'expense_date',
        'receipt_path',
        'notes',
    ];

    protected $casts = [
        'category'     => ExpenseCategory::class,
        'expense_date' => 'date',
        'amount_pkr'   => 'integer',
    ];
}
