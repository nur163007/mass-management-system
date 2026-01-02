<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\AmountHelper;
use App\Services\MemberBalanceService;

class SmartNotificationService
{
    protected $balanceService;

    public function __construct(MemberBalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    /**
     * Get all notifications for admin
     */
    public function getAdminNotifications()
    {
        $notifications = [];
        $month = Carbon::now()->isoFormat('MMM');
        
        // Check for pending meals
        $pendingMeals = DB::select("
            SELECT COUNT(*) as count
            FROM meals
            WHERE status = '0'
        ");
        
        if ($pendingMeals[0]->count > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'meal',
                'title' => 'Pending Meals',
                'message' => "{$pendingMeals[0]->count} meal(s) are pending approval.",
                'action' => 'admin/meal/viewMeal',
                'count' => $pendingMeals[0]->count,
            ];
        }
        
        // Check for pending payments
        $pendingPayments = DB::select("
            SELECT COUNT(*) as count
            FROM payments
            WHERE status = '0'
        ");
        
        if ($pendingPayments[0]->count > 0) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'payment',
                'title' => 'Pending Payments',
                'message' => "{$pendingPayments[0]->count} payment(s) are pending approval.",
                'action' => 'admin/payment/viewPayment',
                'count' => $pendingPayments[0]->count,
            ];
        }
        
        // Check for pending expenses
        $pendingExpenses = DB::select("
            SELECT COUNT(*) as count
            FROM expanses
            WHERE status = '0'
        ");
        
        if ($pendingExpenses[0]->count > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'expense',
                'title' => 'Pending Expenses',
                'message' => "{$pendingExpenses[0]->count} expense(s) are pending approval.",
                'action' => 'admin/expanse/viewExpanse',
                'count' => $pendingExpenses[0]->count,
            ];
        }
        
        // Check for members with due balance
        $membersWithDue = $this->balanceService->getMembersWithDue($month);
        if (count($membersWithDue) > 0) {
            $totalDue = array_sum(array_column($membersWithDue, 'balance'));
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'balance',
                'title' => 'Members with Due Balance',
                'message' => count($membersWithDue) . " member(s) have due balance totaling " . abs(AmountHelper::roundAmount($totalDue)),
                'action' => 'admin/totalSummary',
                'count' => count($membersWithDue),
            ];
        }
        
        // Check for low cash balance
        $meals = DB::select("SELECT * from meals where status = '1' and month = ?", [$month]);
        $br = $ln = $dn = 0;
        foreach($meals as $m) {
            $br += $m->breakfast;
            $ln += $m->lunch;
            $dn += $m->dinner;
        }
        $total_meal = $br + $ln + $dn;
        
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
        
        $cash = $fund - $all_ex;
        
        if ($cash < 0) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'cash',
                'title' => 'Negative Cash Balance',
                'message' => "Current cash balance is negative. Immediate action required.",
                'action' => 'admin/dashboard',
                'count' => 1,
            ];
        } elseif ($cash < 1000) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'cash',
                'title' => 'Low Cash Balance',
                'message' => "Cash balance is low. Consider collecting payments.",
                'action' => 'admin/payment/viewPayment',
                'count' => 1,
            ];
        }
        
        return $notifications;
    }

    /**
     * Get notifications for user
     */
    public function getUserNotifications($memberId)
    {
        $notifications = [];
        $month = Carbon::now()->isoFormat('MMM');
        
        // Check pending meals
        $pendingMeals = DB::select("
            SELECT COUNT(*) as count
            FROM meals
            WHERE members_id = ? AND status = '0'
        ", [$memberId]);
        
        if ($pendingMeals[0]->count > 0) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'meal',
                'title' => 'Pending Meals',
                'message' => "You have {$pendingMeals[0]->count} meal(s) pending approval.",
                'action' => 'user/meal/pendingMeal',
                'count' => $pendingMeals[0]->count,
            ];
        }
        
        // Check balance
        $balance = $this->balanceService->getMemberBalance($memberId, $month);
        
        if ($balance['balance'] < 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'balance',
                'title' => 'Payment Due',
                'message' => "You have a due balance of " . abs(AmountHelper::roundAmount($balance['balance'])) . ". Please make a payment.",
                'action' => 'user/payment/addPayment',
                'count' => 1,
            ];
        }
        
        return $notifications;
    }
}

