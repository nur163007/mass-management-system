<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\SmartAnalyticsService;
use App\Services\RoomRentService;
use App\Services\BillService;
use App\Services\EnhancedMealService;
use App\Helpers\AmountHelper;

class MemberBalanceService
{
    protected $analyticsService;
    protected $roomRentService;
    protected $billService;
    protected $mealService;

    public function __construct(
        SmartAnalyticsService $analyticsService,
        RoomRentService $roomRentService,
        BillService $billService,
        EnhancedMealService $mealService
    ) {
        $this->analyticsService = $analyticsService;
        $this->roomRentService = $roomRentService;
        $this->billService = $billService;
        $this->mealService = $mealService;
    }

    /**
     * Get all members balance for a month
     */
    public function getAllMembersBalance($month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        
        // Exclude Super Admin (role_id = 1), only include Manager (2) and User (3)
        $members = DB::select("
            SELECT id, full_name, email, phone_no
            FROM members
            WHERE status = '1' AND role_id IN (2, 3)
        ");
        
        $balances = [];
        foreach ($members as $member) {
            $balance = $this->getMemberBalance($member->id, $month);
            $balance['member_name'] = $member->full_name;
            $balance['email'] = $member->email;
            $balance['phone'] = $member->phone_no;
            $balances[] = $balance;
        }
        
        // Sort by balance (negative first - those who owe)
        usort($balances, function($a, $b) {
            return $a['balance'] <=> $b['balance'];
        });
        
        return $balances;
    }

    /**
     * Get members with due balance
     */
    public function getMembersWithDue($month = null)
    {
        $allBalances = $this->getAllMembersBalance($month);
        
        return array_filter($allBalances, function($balance) {
            return $balance['balance'] < 0;
        });
    }

    /**
     * Get members summary
     */
    public function getMembersSummary($month = null)
    {
        $allBalances = $this->getAllMembersBalance($month);
        
        $totalDue = 0;
        $totalCredit = 0;
        $totalCollected = 0;
        $totalExpectedCost = 0;
        $membersDue = 0;
        $membersPaid = 0;
        
        foreach ($allBalances as $balance) {
            $totalExpectedCost += $balance['total_cost'];
            $totalCollected += $balance['total_payment'];

            if ($balance['balance'] < 0) {
                $totalDue += abs($balance['balance']);
                $membersDue++;
            } else {
                $totalCredit += $balance['balance'];
                $membersPaid++;
            }
        }
        
        return [
            'total_members' => count($allBalances),
            'members_due' => $membersDue,
            'members_paid' => $membersPaid,
            'total_due' => AmountHelper::roundAmount($totalDue),
            'total_paid' => AmountHelper::roundAmount($totalCredit),
            'total_collected' => AmountHelper::roundAmount($totalCollected),
            'total_expected_cost' => AmountHelper::roundAmount($totalExpectedCost),
            'collection_rate' => $totalExpectedCost > 0 
                ? round(($totalCollected / $totalExpectedCost) * 100, 1) 
                : 0,
        ];
    }

    /**
     * Get comprehensive member balance including room rent, bills, meals
     */
    public function getMemberBalance($memberId, $month = null)
    {
        $month = $month ?? Carbon::now()->format('F');
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        $monthFull = $monthMap[$month] ?? $month;
        $monthAbbr = array_search($monthFull, $monthMap) ?: substr($monthFull, 0, 3);
        
        // Get meal cost
        $mealCost = $this->mealService->getMemberMealCost($memberId, $monthFull);
        $mealCount = $this->mealService->getMemberMealCount($memberId, $monthFull);
        $mealRate = $this->mealService->calculateMealRate($monthFull);
        
        // Get room rent
        $roomRent = $this->roomRentService->getMemberRoomRent($memberId, $monthFull);
        $monthlyRent = $roomRent['final_rent'];
        
        // Get bills (all bills per person)
        $billsTotal = $this->billService->getTotalBillsPerPerson($monthFull);
        
        // Get meal payments (food advance & meal payments)
        $mealPayments = DB::select("
            SELECT COALESCE(SUM(payment_amount), 0) as total_payment
            FROM payments
            WHERE member_id = ? AND (month = ? OR month = ?) AND status = '1' 
            AND payment_type IN ('food_advance', 'meal_expanse_payment', 'meal_payment')
        ", [$memberId, $monthFull, $monthAbbr]);
        
        $mealPaymentTotal = $mealPayments[0]->total_payment ?? 0;
        
        // Get bill payments separately (sum of all bill payment types)
        $billPayments = DB::select("
            SELECT COALESCE(SUM(payment_amount), 0) as total_payment
            FROM payments
            WHERE member_id = ? AND (month = ? OR month = ?) AND status = '1' 
            AND payment_type IN ('water', 'internet', 'electricity', 'gas', 'bua_moyla')
        ", [$memberId, $monthFull, $monthAbbr]);
        
        $billPaymentTotal = $billPayments[0]->total_payment ?? 0;
        
        // Get room rent payments separately
        $roomRentPayments = DB::select("
            SELECT COALESCE(SUM(payment_amount), 0) as total_payment
            FROM payments
            WHERE member_id = ? AND (month = ? OR month = ?) AND status = '1' 
            AND payment_type IN ('room_rent', 'room_advance')
        ", [$memberId, $monthFull, $monthAbbr]);
        
        $roomRentPaymentTotal = $roomRentPayments[0]->total_payment ?? 0;
        
        // Calculate total payment (all payment types)
        $totalPayment = $mealPaymentTotal + $billPaymentTotal + $roomRentPaymentTotal;
        
        // Calculate total cost
        $totalCost = $mealCost + $monthlyRent + $billsTotal;
        
        // Calculate balance (total payment - total cost)
        $balance = $totalPayment - $totalCost;
        
        return [
            'member_id' => $memberId,
            'month' => $monthFull,
            'meal_count' => $mealCount,
            'meal_rate' => AmountHelper::roundAmount($mealRate),
            'meal_cost' => AmountHelper::roundAmount($mealCost),
            'room_rent' => AmountHelper::roundAmount($monthlyRent),
            'room_name' => $roomRent['room_name'],
            'bills_total' => AmountHelper::roundAmount($billsTotal),
            'total_cost' => AmountHelper::roundAmount($totalCost),
            'total_payment' => AmountHelper::roundAmount($totalPayment),
            'meal_payment' => AmountHelper::roundAmount($mealPaymentTotal),
            'bill_payment' => AmountHelper::roundAmount($billPaymentTotal),
            'room_rent_payment' => AmountHelper::roundAmount($roomRentPaymentTotal),
            'balance' => AmountHelper::roundAmount($balance),
            'status' => $balance >= 0 ? 'paid' : 'due',
            'breakdown' => [
                'meal_cost' => AmountHelper::roundAmount($mealCost),
                'room_rent' => AmountHelper::roundAmount($monthlyRent),
                'bills' => AmountHelper::roundAmount($billsTotal),
                'meal_payment' => AmountHelper::roundAmount($mealPaymentTotal),
                'bill_payment' => AmountHelper::roundAmount($billPaymentTotal),
                'room_rent_payment' => AmountHelper::roundAmount($roomRentPaymentTotal),
            ],
        ];
    }
}

