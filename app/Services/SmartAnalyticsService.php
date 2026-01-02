<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Helpers\AmountHelper;

class SmartAnalyticsService
{
    /**
     * Get smart dashboard analytics with predictions
     */
    public function getDashboardAnalytics($month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        
        return Cache::remember("dashboard_analytics_{$month}", 3600, function () use ($month) {
            // Current month data
            $currentData = $this->getMonthData($month);
            
            // Previous month data for comparison
            $prevMonth = Carbon::now()->subMonth()->isoFormat('MMM');
            $prevData = $this->getMonthData($prevMonth);
            
            // Calculate trends
            $trends = $this->calculateTrends($currentData, $prevData);
            
            // Predictions
            $predictions = $this->getPredictions($currentData, $prevData);
            
            // Member insights
            $memberInsights = $this->getMemberInsights($month);
            
            // Expense insights
            $expenseInsights = $this->getExpenseInsights($month);
            
            return [
                'current' => $currentData,
                'previous' => $prevData,
                'trends' => $trends,
                'predictions' => $predictions,
                'member_insights' => $memberInsights,
                'expense_insights' => $expenseInsights,
            ];
        });
    }

    /**
     * Get month data
     */
    private function getMonthData($month)
    {
        // Use enhanced meal service for accurate meal count
        $mealService = app(\App\Services\EnhancedMealService::class);
        $total_meal = $mealService->getTotalMealCount($month);
        
        // Keep backward compatibility for breakfast, lunch, dinner counts
        $meals = DB::select("SELECT * from meals where status = '1' and month = ?", [$month]);
        $br = 0;
        $ln = 0;
        $dn = 0;
        foreach($meals as $m) {
            $br += $m->breakfast ?? 0;
            $ln += $m->lunch ?? 0;
            $dn += $m->dinner ?? 0;
        }
        
        $payments = DB::select("SELECT * from payments where status ='1' and month = ?", [$month]);
        $fund = 0;
        foreach($payments as $pay) {
            $fund += $pay->payment_amount;
        }
        
        $expanses = DB::select("SELECT total_amount from expanses where status ='1' and month = ?", [$month]);
        $all_ex = 0;
        foreach($expanses as $exp) {
            $all_ex += $exp->total_amount;
        }
        
        $meal_rate = $total_meal > 0 ? ($all_ex / $total_meal) : 0;
        $cash = ($fund - $all_ex);
        
        // Exclude Super Admin (role_id = 1), only count Manager (2) and User (3)
        $members = DB::select("SELECT count('id') as total_member from members where status = '1' AND role_id IN (2, 3)");
        
        return [
            'total_meal' => $total_meal,
            'breakfast' => $br,
            'lunch' => $ln,
            'dinner' => $dn,
            'fund' => $fund,
            'expense' => $all_ex,
            'meal_rate' => AmountHelper::roundAmount($meal_rate),
            'cash' => $cash,
            'members' => $members[0]->total_member ?? 0,
        ];
    }

    /**
     * Calculate trends
     */
    private function calculateTrends($current, $previous)
    {
        $calculatePercentage = function($current, $previous) {
            if ($previous == 0) return $current > 0 ? 100 : 0;
            return round((($current - $previous) / $previous) * 100, 2);
        };
        
        return [
            'meal_trend' => $calculatePercentage($current['total_meal'], $previous['total_meal']),
            'expense_trend' => $calculatePercentage($current['expense'], $previous['expense']),
            'fund_trend' => $calculatePercentage($current['fund'], $previous['fund']),
            'meal_rate_trend' => $calculatePercentage($current['meal_rate'], $previous['meal_rate']),
        ];
    }

    /**
     * Get predictions for next month
     */
    private function getPredictions($current, $previous)
    {
        // Simple moving average prediction
        $avgMeal = ($current['total_meal'] + $previous['total_meal']) / 2;
        $avgExpense = ($current['expense'] + $previous['expense']) / 2;
        $predictedMealRate = $avgMeal > 0 ? ($avgExpense / $avgMeal) : 0;
        
        // Calculate expected fund needed
        $expectedFund = $avgExpense + ($avgExpense * 0.1); // 10% buffer
        
        return [
            'predicted_meals' => round($avgMeal),
            'predicted_expense' => AmountHelper::roundAmount($avgExpense),
            'predicted_meal_rate' => AmountHelper::roundAmount($predictedMealRate),
            'expected_fund' => AmountHelper::roundAmount($expectedFund),
            'fund_gap' => AmountHelper::roundAmount($expectedFund - $current['fund']),
        ];
    }

    /**
     * Get member insights
     */
    private function getMemberInsights($month)
    {
        // Exclude Super Admin (role_id = 1), only include Manager (2) and User (3)
        $memberStats = DB::select("
            SELECT 
                m.id,
                m.full_name,
                SUM(meals.breakfast + meals.lunch + meals.dinner) as total_meals,
                COALESCE(SUM(payments.payment_amount), 0) as total_payment
            FROM members m
            LEFT JOIN meals ON m.id = meals.members_id AND meals.month = ? AND meals.status = '1'
            LEFT JOIN payments ON m.id = payments.member_id AND payments.month = ? AND payments.status = '1'
            WHERE m.status = '1' AND m.role_id IN (2, 3)
            GROUP BY m.id, m.full_name
        ", [$month, $month]);
        
        $insights = [];
        foreach ($memberStats as $stat) {
            $insights[] = [
                'member_id' => $stat->id,
                'name' => $stat->full_name,
                'total_meals' => $stat->total_meals ?? 0,
                'total_payment' => $stat->total_payment ?? 0,
            ];
        }
        
        // Sort by meals (top consumers)
        usort($insights, function($a, $b) {
            return $b['total_meals'] <=> $a['total_meals'];
        });
        
        return [
            'top_consumers' => array_slice($insights, 0, 5),
            'low_consumers' => array_slice(array_reverse($insights), 0, 5),
            'all_members' => $insights,
        ];
    }

    /**
     * Get expense insights
     */
    private function getExpenseInsights($month)
    {
        $categoryExpenses = DB::select("
            SELECT 
                c.category_name,
                SUM(e.total_amount) as total_expense,
                COUNT(e.id) as expense_count
            FROM expanses e
            INNER JOIN food_categories c ON e.category_id = c.id
            WHERE e.status = '1' AND e.month = ?
            GROUP BY c.id, c.category_name
            ORDER BY total_expense DESC
        ", [$month]);
        
        $itemExpenses = DB::select("
            SELECT 
                fi.item_name,
                SUM(ed.amount) as total_amount,
                COUNT(ed.id) as purchase_count
            FROM expanse_details ed
            INNER JOIN food_items fi ON ed.item_name_id = fi.id
            INNER JOIN expanses e ON ed.invoice_no = e.invoice_no
            WHERE e.status = '1' AND e.month = ?
            GROUP BY fi.id, fi.item_name
            ORDER BY total_amount DESC
            LIMIT 10
        ", [$month]);
        
        return [
            'by_category' => $categoryExpenses,
            'top_items' => $itemExpenses,
        ];
    }

    /**
     * Get member balance details
     */
    public function getMemberBalance($memberId, $month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        
        // Get member meals
        $meals = DB::select("
            SELECT SUM(breakfast + lunch + dinner) as total_meals
            FROM meals
            WHERE members_id = ? AND month = ? AND status = '1'
        ", [$memberId, $month]);
        
        $totalMeals = $meals[0]->total_meals ?? 0;
        
        // Get member payments
        $payments = DB::select("
            SELECT SUM(payment_amount) as total_payment
            FROM payments
            WHERE member_id = ? AND month = ? AND status = '1'
        ", [$memberId, $month]);
        
        $totalPayment = $payments[0]->total_payment ?? 0;
        
        // Get current meal rate
        $analytics = $this->getDashboardAnalytics($month);
        $mealRate = $analytics['current']['meal_rate'];
        
        // Calculate balance
        $mealCost = $totalMeals * $mealRate;
        $balance = $totalPayment - $mealCost;
        
        return [
            'member_id' => $memberId,
            'month' => $month,
            'total_meals' => $totalMeals,
            'total_payment' => $totalPayment,
            'meal_rate' => $mealRate,
            'meal_cost' => AmountHelper::roundAmount($mealCost),
            'balance' => AmountHelper::roundAmount($balance),
            'status' => $balance >= 0 ? 'paid' : 'due',
        ];
    }

    /**
     * Get expense optimization suggestions
     */
    public function getOptimizationSuggestions($month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        
        $suggestions = [];
        
        // Get average expense per meal
        $analytics = $this->getDashboardAnalytics($month);
        $avgExpensePerMeal = $analytics['current']['meal_rate'];
        
        // Get previous months for comparison
        $prevMonths = [];
        for ($i = 1; $i <= 3; $i++) {
            $prevMonth = Carbon::now()->subMonths($i)->isoFormat('MMM');
            $prevData = $this->getMonthData($prevMonth);
            $prevMonths[] = $prevData['meal_rate'];
        }
        
        $avgPrevRate = count($prevMonths) > 0 ? array_sum($prevMonths) / count($prevMonths) : 0;
        
        // Suggestion 1: Meal rate comparison
        if ($avgExpensePerMeal > $avgPrevRate * 1.15) {
            $suggestions[] = [
                'type' => 'warning',
                'title' => 'High Meal Rate Detected',
                'message' => "Current meal rate ({$avgExpensePerMeal}) is 15% higher than average. Consider reviewing expenses.",
                'action' => 'Review expense categories and find cost-saving opportunities.',
            ];
        }
        
        // Suggestion 2: Low cash balance
        if ($analytics['current']['cash'] < 0) {
            $suggestions[] = [
                'type' => 'danger',
                'title' => 'Negative Cash Balance',
                'message' => "Current cash balance is negative. Collect payments from members.",
                'action' => 'Send payment reminders to members with due balances.',
            ];
        }
        
        // Suggestion 3: Expense category analysis
        $expenseInsights = $this->getExpenseInsights($month);
        if (count($expenseInsights['by_category']) > 0) {
            $topCategory = $expenseInsights['by_category'][0];
            $totalExpense = $analytics['current']['expense'];
            $categoryPercentage = ($topCategory->total_expense / $totalExpense) * 100;
            
            if ($categoryPercentage > 60) {
                $suggestions[] = [
                    'type' => 'info',
                    'title' => 'Expense Concentration',
                    'message' => "{$topCategory->category_name} accounts for " . round($categoryPercentage, 1) . "% of total expenses.",
                    'action' => 'Consider diversifying purchases to reduce dependency on single category.',
                ];
            }
        }
        
        return $suggestions;
    }

    /**
     * Clear analytics cache
     */
    public function clearCache($month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        Cache::forget("dashboard_analytics_{$month}");
    }
}

