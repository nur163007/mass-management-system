<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    
    protected $table = 'members';
    
    protected $fillable = [
        'full_name',
        'phone_no',
        'address',
        'email',
        'password',
        'photo',
        'nid_photo',
        'role_id',
        'role_name',
        'status',
        'current_room_id',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationships
    public function currentRoom()
    {
        return $this->belongsTo(Room::class, 'current_room_id');
    }

    public function roomMembers()
    {
        return $this->hasMany(RoomMember::class);
    }

    public function activeRoomMember()
    {
        return $this->hasOne(RoomMember::class)->where('status', 1);
    }

    public function roomAdvances()
    {
        return $this->hasMany(RoomAdvance::class);
    }

    public function serviceCharge()
    {
        return $this->hasOne(ServiceCharge::class);
    }

    public function extraPayments()
    {
        return $this->hasMany(MemberExtraPayment::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class, 'members_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
