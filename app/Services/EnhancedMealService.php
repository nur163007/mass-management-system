<?php

namespace App\Services;

use App\Models\Meal;
use App\Models\Expanse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\AmountHelper;

class EnhancedMealService
{
    /**
     * Calculate meal rate for a month
     * Meal Rate = Total Bazar Cost / Total Meal Count
     */
    public function calculateMealRate($month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        
        // Get total bazar cost (expanses)
        $totalBazar = Expanse::where('month', $month)
            ->where('status', 1)
            ->sum('total_amount');
        
        // Get total meal count for the month
        $totalMealCount = $this->getTotalMealCount($month);
        
        if ($totalMealCount > 0) {
            $rate = $totalBazar / $totalMealCount;
            return AmountHelper::roundAmount($rate);
        }
        
        return 0;
    }

    /**
     * Get total meal count for a month (using new counting rules)
     */
    public function getTotalMealCount($month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        
        $meals = Meal::where('month', $month)
            ->where('status', 1)
            ->get();
        
        $total = 0;
        foreach ($meals as $meal) {
            // Calculate and update total_meal_count if not set
            if (is_null($meal->total_meal_count)) {
                $meal->total_meal_count = $meal->calculateTotalMealCount();
                $meal->save();
            }
            $total += $meal->total_meal_count;
        }
        
        return $total;
    }

    /**
     * Get member's total meal count for a month
     */
    public function getMemberMealCount($memberId, $month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        
        $meals = Meal::where('members_id', $memberId)
            ->where('month', $month)
            ->where('status', 1)
            ->get();
        
        $total = 0;
        foreach ($meals as $meal) {
            if (is_null($meal->total_meal_count)) {
                $meal->total_meal_count = $meal->calculateTotalMealCount();
                $meal->save();
            }
            $total += $meal->total_meal_count;
        }
        
        return $total;
    }

    /**
     * Get member's meal cost for a month
     */
    public function getMemberMealCost($memberId, $month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        $mealCount = $this->getMemberMealCount($memberId, $month);
        $mealRate = $this->calculateMealRate($month);
        
        return AmountHelper::roundAmount($mealCount * $mealRate);
    }
}

