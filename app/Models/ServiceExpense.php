<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_type',
        'amount',
        'expense_date',
        'month',
        'description',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];
}

