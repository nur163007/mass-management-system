<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillTypeResponsibility extends Model
{
    protected $fillable = [
        'member_id',
        'bill_type',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Bill type constants
    const TYPE_WATER = 'water';
    const TYPE_INTERNET = 'internet';
    const TYPE_ELECTRICITY = 'electricity';
    const TYPE_GAS = 'gas';
    const TYPE_BUA_MOYLA = 'bua_moyla';
    const TYPE_ROOM_RENT = 'room_rent';
    const TYPE_ROOM_ADVANCE = 'room_advance';
    const TYPE_FOOD_ADVANCE = 'food_advance';

    public static function getBillTypes()
    {
        return [
            self::TYPE_WATER => 'Water Bill',
            self::TYPE_INTERNET => 'Internet Bill',
            self::TYPE_ELECTRICITY => 'Electricity Bill',
            self::TYPE_GAS => 'Gas Bill',
            self::TYPE_BUA_MOYLA => 'Bua & Moyla Bill',
            self::TYPE_ROOM_RENT => 'Room Rent',
            self::TYPE_ROOM_ADVANCE => 'Room Advance',
            self::TYPE_FOOD_ADVANCE => 'Food Advance',
        ];
    }
}
