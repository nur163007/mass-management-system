<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'room_id',
        'amount',
        'payment_date',
        'refunded',
        'refunded_date',
        'refunded_amount',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'payment_date' => 'date',
        'refunded_date' => 'date',
        'refunded' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

