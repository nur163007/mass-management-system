<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'member_id',
        'advance_paid',
        'monthly_rent',
        'assigned_date',
        'left_date',
        'status',
    ];

    protected $casts = [
        'advance_paid' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'assigned_date' => 'date',
        'left_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

