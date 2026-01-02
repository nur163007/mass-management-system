<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    
    protected $table = 'meals';
    
    protected $fillable = [
        'members_id',
        'date',
        'month',
        'breakfast',
        'lunch',
        'dinner',
        'lunch_only_curry',
        'dinner_only_curry',
        'total_meal_count',
        'status',
    ];

    protected $casts = [
        'breakfast' => 'integer', // Will be converted: 1 = 0.5 meal, 2 = 1 meal in calculation
        'lunch' => 'integer',
        'dinner' => 'integer',
        'lunch_only_curry' => 'decimal:2',
        'dinner_only_curry' => 'decimal:2',
        'total_meal_count' => 'decimal:2',
        'date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'members_id');
    }

    /**
     * Calculate total meal count based on new rules
     * Breakfast = 0.5 per count, Lunch = 1, Dinner = 1, Lunch only curry = 0.75, Dinner only curry = 0.75
     */
    public function calculateTotalMealCount()
    {
        $total = 0;
        $total += $this->breakfast * 0.5; // Breakfast = 0.5 meal per count (if breakfast=1, then 0.5 meal)
        $total += $this->lunch * 1; // Lunch = 1 meal
        $total += $this->dinner * 1; // Dinner = 1 meal
        $total += ($this->lunch_only_curry ?? 0) * 0.75; // Lunch only curry = 0.75 meal
        $total += ($this->dinner_only_curry ?? 0) * 0.75; // Dinner only curry = 0.75 meal
        
        return round($total, 2);
    }
}
