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
        'applicable_member_ids',
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
        'applicable_member_ids' => 'array',
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
        // Get applicable member count from stored member IDs if available
        $applicableMemberIds = is_array($this->applicable_member_ids) ? $this->applicable_member_ids : [];
        $memberCount = !empty($applicableMemberIds) ? count($applicableMemberIds) : $this->applicable_members;
        
        if ($this->bill_type === self::TYPE_GAS) {
            // Gas bill calculation with dynamic extra users:
            // 1. Calculate total: cylinder_count × cylinder_cost
            // 2. Calculate extra amount: extra_users_count × 100tk
            // 3. Calculate remaining: total - extra_amount
            // 4. Base per person: remaining ÷ applicable_member_count
            // 5. Extra users pay: base_amount + 100tk
            // Example: 1500tk total, 3 extra users → 300tk extra → 1200tk remaining → divide by applicable members
            
            $extraUsersCount = is_array($this->extra_gas_users) ? count($this->extra_gas_users) : 0;
            $extraTotal = $extraUsersCount * 100; // Total extra amount (extra_users × 100tk)
            $total = ($this->cylinder_count ?? 1) * ($this->cylinder_cost ?? 1500);
            $remaining = $total - $extraTotal; // Remaining amount after deducting extra payments
            $amount = $remaining / $memberCount; // Base per person (extra users will pay this + 100tk)
            return AmountHelper::roundAmount($amount);
        }

        $amount = $this->total_amount / $memberCount;
        return AmountHelper::roundAmount($amount);
    }
    
    /**
     * Get per person amount for a specific member (for gas bills with extra users)
     */
    public function getPerPersonAmountForMember($memberId)
    {
        // Check if member is in the applicable members list
        $applicableMemberIds = is_array($this->applicable_member_ids) ? $this->applicable_member_ids : [];
        if (!empty($applicableMemberIds) && !in_array($memberId, $applicableMemberIds)) {
            // Member is not applicable for this bill
            return 0;
        }
        
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

