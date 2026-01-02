<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $table = 'payments';
    
    protected $fillable = [
        'member_id',
        'payment_amount',
        'payment_type',
        'date',
        'month',
        'status',
        'notes',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
