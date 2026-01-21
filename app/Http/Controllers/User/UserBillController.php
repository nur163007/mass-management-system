<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserBillController extends Controller
{
    /**
     * Display list of bills for logged-in user
     */
    public function index(Request $request)
    {
        $id = session()->get('member_id');
        $monthInput = $request->input('month', Carbon::now()->format('F'));
        
        // Convert abbreviated month to full form if needed
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        
        $month = $monthMap[$monthInput] ?? $monthInput;
        
        // Get abbreviated form for querying (in case database has abbreviated months)
        $reverseMap = array_flip($monthMap);
        $monthAbbr = $reverseMap[$month] ?? null;
        
        // Get all active bills for the selected month (check both full and abbreviated forms)
        $bills = Bill::where(function($query) use ($month, $monthAbbr) {
                $query->where('month', $month);
                if ($monthAbbr) {
                    $query->orWhere('month', $monthAbbr);
                }
            })
            ->where('status', 1)
            ->orderBy('bill_date', 'DESC')
            ->get();
        
        // Get unique months for dropdown and convert to full form
        $monthsFromDb = Bill::where('status', 1)
            ->select('month')
            ->distinct()
            ->pluck('month');
        
        // Convert all months to full form and get unique
        $months = $monthsFromDb->map(function($m) use ($monthMap) {
            return $monthMap[$m] ?? $m;
        })->unique()->sortBy(function($m) {
            $order = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $index = array_search($m, $order);
            return $index !== false ? $index : 999;
        })->values();
        
        return view('userpanel.bill.index', compact('bills', 'month', 'months'));
    }

    /**
     * Display bill details with payment information
     */
    public function view($id, Request $request)
    {
        $bill = Bill::findOrFail($id);
        
        // Month format conversion map
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        
        // Get selected month from request, default to current month (not bill's month)
        $currentMonth = Carbon::now()->format('F'); // Current month in full form
        $selectedMonth = $request->input('month', $currentMonth);
        
        // Convert selected month to full form
        $selectedMonthFull = $monthMap[$selectedMonth] ?? $selectedMonth;
        
        // Get abbreviated form if selected month is in full form
        $reverseMap = array_flip($monthMap);
        $selectedMonthAbbr = $reverseMap[$selectedMonthFull] ?? null;
        
        // If selected month is abbreviated, get full form
        if (isset($monthMap[$selectedMonth])) {
            $selectedMonthFull = $monthMap[$selectedMonth];
        }
        
        // Convert bill month to full form (for display)
        $billMonth = $bill->month;
        $billMonthFull = $monthMap[$billMonth] ?? $billMonth;
        if (isset($monthMap[$billMonth])) {
            $billMonthFull = $monthMap[$billMonth];
        }
        
        // Query payments: Filter by payment_type AND selected month (check both formats)
        // Exclude Super Admin payments
        $payments = Payment::where('payment_type', $bill->bill_type)
            ->where(function($query) use ($selectedMonth, $selectedMonthFull, $selectedMonthAbbr) {
                // Check original selected month format
                $query->where('payments.month', $selectedMonth);
                
                // Check full form if different
                if ($selectedMonthFull != $selectedMonth) {
                    $query->orWhere('payments.month', $selectedMonthFull);
                }
                
                // Check abbreviated form if different
                if ($selectedMonthAbbr && $selectedMonthAbbr != $selectedMonth && $selectedMonthAbbr != $selectedMonthFull) {
                    $query->orWhere('payments.month', $selectedMonthAbbr);
                }
            })
            ->where('payments.status', 1)
            ->join('members', 'payments.member_id', '=', 'members.id')
            ->where('members.role_id', '!=', 1) // Exclude Super Admin payments
            ->select('payments.*', 'members.full_name', 'members.phone_no')
            ->orderBy('payments.date', 'DESC')
            ->get();
        
        // Calculate totals based on filtered payments
        $totalPaid = $payments->sum('payment_amount');
        $totalPaidCount = $payments->count();
        $uniquePaidMembers = $payments->pluck('member_id')->unique()->count();
        
        // Get bill type name
        $billTypeNames = [
            'water' => 'Water Bill',
            'internet' => 'Internet Bill',
            'electricity' => 'Electricity Bill',
            'gas' => 'Gas Bill',
            'bua_moyla' => 'Bua & Moyla Bill',
        ];
        
        $billTypeName = $billTypeNames[$bill->bill_type] ?? ucfirst($bill->bill_type);
        
        // Get applicable members from stored member IDs
        $applicableMemberIds = is_array($bill->applicable_member_ids) ? $bill->applicable_member_ids : [];
        
        // If no member IDs stored (old bills), fallback to old logic
        if (empty($applicableMemberIds)) {
            $applicableMembersCount = $bill->bill_type === Bill::TYPE_INTERNET ? 6 : 7;
            $allMembers = \App\Models\Member::whereIn('role_id', [2, 3])
                ->where('status', 1)
                ->orderBy('full_name')
                ->limit($applicableMembersCount)
                ->get();
        } else {
            // Get only the selected members
            $allMembers = \App\Models\Member::whereIn('id', $applicableMemberIds)
                ->where('status', 1)
                ->orderBy('full_name')
                ->get();
        }
        
        // Group payments by member for quick lookup
        $paymentsByMemberId = $payments->groupBy('member_id')->map(function ($memberPayments) {
            return [
                'member_name' => $memberPayments->first()->full_name,
                'phone_no' => $memberPayments->first()->phone_no,
                'total_paid' => $memberPayments->sum('payment_amount'),
                'payment_count' => $memberPayments->count(),
                'payments' => $memberPayments
            ];
        });
        
        // Create list with all applicable members, including those who haven't paid
        $paymentsByMember = $allMembers->mapWithKeys(function ($member) use ($paymentsByMemberId, $bill) {
            $memberId = $member->id;
            
            // Check if member has made payments
            if ($paymentsByMemberId->has($memberId)) {
                // Member has paid - use existing data
                $data = $paymentsByMemberId->get($memberId);
                $data['member_id'] = $memberId; // Ensure member_id is set
                return [$memberId => $data];
            } else {
                // Member hasn't paid yet
                return [$memberId => [
                    'member_id' => $memberId,
                    'member_name' => $member->full_name,
                    'phone_no' => $member->phone_no ?? 'N/A',
                    'total_paid' => 0,
                    'payment_count' => 0,
                    'payments' => collect([])
                ]];
            }
        });
        
        return view('userpanel.bill.view', compact(
            'bill',
            'billTypeName',
            'payments',
            'paymentsByMember',
            'totalPaid',
            'totalPaidCount',
            'uniquePaidMembers',
            'selectedMonthFull',
            'billMonthFull'
        ));
    }
}

