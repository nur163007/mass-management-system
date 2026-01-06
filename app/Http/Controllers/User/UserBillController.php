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
    public function view($id)
    {
        $bill = Bill::findOrFail($id);
        
        // Month format conversion map
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        
        // Convert bill month to full form
        $billMonth = $bill->month;
        $billMonthFull = $monthMap[$billMonth] ?? $billMonth;
        
        // Get abbreviated form if bill month is in full form
        $reverseMap = array_flip($monthMap);
        $billMonthAbbr = $reverseMap[$billMonthFull] ?? null;
        
        // If bill month is abbreviated, get full form
        if (isset($monthMap[$billMonth])) {
            $billMonthFull = $monthMap[$billMonth];
        }
        
        // Query payments: Filter by payment_type AND month (check both formats)
        $payments = Payment::where('payment_type', $bill->bill_type)
            ->where(function($query) use ($billMonth, $billMonthFull, $billMonthAbbr) {
                // Check original bill month format
                $query->where('payments.month', $billMonth);
                
                // Check full form if different
                if ($billMonthFull != $billMonth) {
                    $query->orWhere('payments.month', $billMonthFull);
                }
                
                // Check abbreviated form if different
                if ($billMonthAbbr && $billMonthAbbr != $billMonth && $billMonthAbbr != $billMonthFull) {
                    $query->orWhere('payments.month', $billMonthAbbr);
                }
            })
            ->where('payments.status', 1)
            ->join('members', 'payments.member_id', '=', 'members.id')
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
        
        // Group payments by member
        $paymentsByMember = $payments->groupBy('member_id')->map(function ($memberPayments) {
            return [
                'member_name' => $memberPayments->first()->full_name,
                'phone_no' => $memberPayments->first()->phone_no,
                'total_paid' => $memberPayments->sum('payment_amount'),
                'payment_count' => $memberPayments->count(),
                'payments' => $memberPayments
            ];
        });
        
        return view('userpanel.bill.view', compact(
            'bill',
            'billTypeName',
            'payments',
            'paymentsByMember',
            'totalPaid',
            'totalPaidCount',
            'uniquePaidMembers'
        ));
    }
}

