<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodAdvance extends Model
{
    protected $fillable = [
        'member_id',
        'amount',
        'date',
        'month',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
