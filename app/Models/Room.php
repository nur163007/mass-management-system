<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'room_type',
        'monthly_rent',
        'capacity',
        'advance_amount_per_person_1',
        'advance_amount_per_person_2',
        'status',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'advance_amount_per_person_1' => 'integer',
        'advance_amount_per_person_2' => 'integer',
    ];

    public function roomMembers()
    {
        return $this->hasMany(RoomMember::class)->where('status', 1);
    }

    public function allRoomMembers()
    {
        return $this->hasMany(RoomMember::class);
    }
}

