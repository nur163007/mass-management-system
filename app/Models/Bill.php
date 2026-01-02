<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\AmountHelper;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_type',
        'total_amount',
        'applicable_members',
        'month',
        'bill_date',
        'due_date',
        'cylinder_count',
        'cylinder_cost',
        'extra_gas_users',
        'minimum_per_person',
        'notes',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'cylinder_cost' => 'decimal:2',
        'minimum_per_person' => 'decimal:2',
        'bill_date' => 'date',
        'due_date' => 'date',
        'extra_gas_users' => 'array',
    ];

    // Bill types
    const TYPE_WATER = 'water';
    const TYPE_INTERNET = 'internet';
    const TYPE_ELECTRICITY = 'electricity';
    const TYPE_GAS = 'gas';
    const TYPE_BUA = 'bua';
    const TYPE_MOYLA = 'moyla';

    public function getPerPersonAmountAttribute()
    {
        if ($this->bill_type === self::TYPE_GAS) {
            // Gas calculation: (total - extra payments) / 7 = base per person
            $extraUsersCount = is_array($this->extra_gas_users) ? count($this->extra_gas_users) : 0;
            $extraTotal = $extraUsersCount * 100;
            $total = ($this->cylinder_count ?? 1) * ($this->cylinder_cost ?? 1500);
            $remaining = $total - $extraTotal;
            $amount = $remaining / 7; // Base per person (extra users will pay this + 100)
            return AmountHelper::roundAmount($amount);
        }

        $amount = $this->total_amount / $this->applicable_members;
        return AmountHelper::roundAmount($amount);
    }
    
    /**
     * Get per person amount for a specific member (for gas bills with extra users)
     */
    public function getPerPersonAmountForMember($memberId)
    {
        if ($this->bill_type === self::TYPE_GAS) {
            $baseAmount = $this->per_person_amount;
            $extraUsers = is_array($this->extra_gas_users) ? $this->extra_gas_users : [];
            
            // If member is in extra users list, add 100tk
            if (in_array($memberId, $extraUsers)) {
                return AmountHelper::roundAmount($baseAmount + 100);
            }
            
            return $baseAmount;
        }
        
        return $this->per_person_amount;
    }
}

