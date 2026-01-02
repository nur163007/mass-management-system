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
        $totalPaid = 0;
        $membersDue = 0;
        $membersPaid = 0;
        
        foreach ($allBalances as $balance) {
            if ($balance['balance'] < 0) {
                $totalDue += abs($balance['balance']);
                $membersDue++;
            } else {
                $totalPaid += $balance['balance'];
                $membersPaid++;
            }
        }
        
        return [
            'total_members' => count($allBalances),
            'members_due' => $membersDue,
            'members_paid' => $membersPaid,
            'total_due' => AmountHelper::roundAmount($totalDue),
            'total_paid' => AmountHelper::roundAmount($totalPaid),
            'collection_rate' => count($allBalances) > 0 
                ? round(($membersPaid / count($allBalances)) * 100, 2) 
                : 0,
        ];
    }

    /**
     * Get comprehensive member balance including room rent, bills, meals
     */
    public function getMemberBalance($memberId, $month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        
        // Get meal cost
        $mealCost = $this->mealService->getMemberMealCost($memberId, $month);
        $mealCount = $this->mealService->getMemberMealCount($memberId, $month);
        $mealRate = $this->mealService->calculateMealRate($month);
        
        // Get room rent
        $roomRent = $this->roomRentService->getMemberRoomRent($memberId, $month);
        $monthlyRent = $roomRent['final_rent'];
        
        // Get bills (all bills per person)
        $billsTotal = $this->billService->getTotalBillsPerPerson($month);
        
        // Get meal payments (food advance payments)
        $mealPayments = DB::select("
            SELECT SUM(payment_amount) as total_payment
            FROM payments
            WHERE member_id = ? AND month = ? AND status = '1' AND payment_type = 'food_advance'
        ", [$memberId, $month]);
        
        $mealPaymentTotal = $mealPayments[0]->total_payment ?? 0;
        
        // Get bill payments separately (sum of all bill payment types)
        $billPayments = DB::select("
            SELECT SUM(payment_amount) as total_payment
            FROM payments
            WHERE member_id = ? AND month = ? AND status = '1' AND payment_type IN ('water', 'internet', 'electricity', 'gas', 'bua_moyla')
        ", [$memberId, $month]);
        
        $billPaymentTotal = $billPayments[0]->total_payment ?? 0;
        
        // Get room rent payments separately
        $roomRentPayments = DB::select("
            SELECT SUM(payment_amount) as total_payment
            FROM payments
            WHERE member_id = ? AND month = ? AND status = '1' AND payment_type = 'room_rent'
        ", [$memberId, $month]);
        
        $roomRentPaymentTotal = $roomRentPayments[0]->total_payment ?? 0;
        
        // Calculate total payment (all payment types)
        $totalPayment = $mealPaymentTotal + $billPaymentTotal + $roomRentPaymentTotal;
        
        // Calculate adjusted room rent (monthly rent - room rent payments)
        $adjustedRoomRent = max(0, $monthlyRent - $roomRentPaymentTotal);
        
        // Calculate total cost
        $totalCost = $mealCost + $adjustedRoomRent + $billsTotal;
        
        // Calculate balance (total payment - total cost)
        $balance = $totalPayment - $totalCost;
        
        return [
            'member_id' => $memberId,
            'month' => $month,
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
                'adjusted_room_rent' => AmountHelper::roundAmount($adjustedRoomRent),
                'bills' => AmountHelper::roundAmount($billsTotal),
                'meal_payment' => AmountHelper::roundAmount($mealPaymentTotal),
                'bill_payment' => AmountHelper::roundAmount($billPaymentTotal),
                'room_rent_payment' => AmountHelper::roundAmount($roomRentPaymentTotal),
            ],
        ];
    }
}

