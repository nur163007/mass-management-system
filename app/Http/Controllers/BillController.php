<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Member;
use App\Models\Payment;
use App\Services\BillService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillController extends Controller
{
    protected $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    /**
     * View all bills
     */
    public function index(Request $request)
    {
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
        
        return view('admin.bill.index', compact('bills', 'month', 'months'));
    }

    /**
     * Show form to add bill
     */
    public function create()
    {
        $billTypes = [
            Bill::TYPE_WATER => 'Water Bill',
            Bill::TYPE_INTERNET => 'Internet Bill',
            Bill::TYPE_ELECTRICITY => 'Electricity Bill',
            Bill::TYPE_GAS => 'Gas Bill',
            'bua_moyla' => 'Bua & Moyla Bill',
        ];
        
        // Get Manager (2) and User (3) members only, exclude Super Admin (1)
        $members = Member::whereIn('role_id', [2, 3])->where('status', 1)->get();
        
        return view('admin.bill.create', compact('billTypes', 'members'));
    }

    /**
     * Store new bill
     */
    public function store(Request $request)
    {
        $validationRules = [
            'bill_type' => 'required|in:water,internet,electricity,gas,bua_moyla',
            'bill_date' => 'required|date',
            'month' => 'required',
        ];

        // For gas bills, validate cylinder fields instead of total_amount
        if ($request->bill_type === Bill::TYPE_GAS) {
            $validationRules['cylinder_count'] = 'required|numeric|min:1';
            $validationRules['cylinder_cost'] = 'required|numeric|min:0';
            $validationRules['extra_gas_users'] = 'nullable|array';
            $validationRules['extra_gas_users.*'] = 'exists:members,id';
        } else {
            $validationRules['total_amount'] = 'required|numeric|min:0';
        }

        $this->validate($request, $validationRules);

        $data = $request->all();
        
        // Set applicable members based on bill type
        if ($request->bill_type === Bill::TYPE_INTERNET) {
            $data['applicable_members'] = 6;
        } else {
            $data['applicable_members'] = 7;
        }

        // Handle gas bill special fields
        if ($request->bill_type === Bill::TYPE_GAS) {
            $data['cylinder_count'] = $request->cylinder_count ?? 1;
            $data['cylinder_cost'] = $request->cylinder_cost ?? 1500;
            $data['extra_gas_users'] = $request->extra_gas_users ?? [];
            $data['total_amount'] = $data['cylinder_count'] * $data['cylinder_cost'];
        }

        // Handle electricity minimum
        if ($request->bill_type === Bill::TYPE_ELECTRICITY) {
            $data['minimum_per_person'] = $request->minimum_per_person ?? 200;
        }

        try {
            $this->billService->createBill($data);
            return redirect()->route('admin.bill.index')
                ->with('success', 'Bill added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit bill
     */
    public function edit($id)
    {
        $bill = Bill::findOrFail($id);
        $billTypes = [
            Bill::TYPE_WATER => 'Water Bill',
            Bill::TYPE_INTERNET => 'Internet Bill',
            Bill::TYPE_ELECTRICITY => 'Electricity Bill',
            Bill::TYPE_GAS => 'Gas Bill',
            'bua_moyla' => 'Bua & Moyla Bill',
        ];
        // Get Manager (2) and User (3) members only, exclude Super Admin (1)
        $members = Member::whereIn('role_id', [2, 3])->where('status', 1)->get();
        
        return view('admin.bill.edit', compact('bill', 'billTypes', 'members'));
    }

    /**
     * Update bill
     */
    public function update(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);
        
        $validationRules = [
            'bill_type' => 'required|in:water,internet,electricity,gas,bua_moyla',
            'bill_date' => 'required|date',
            'month' => 'required',
        ];

        // For gas bills, validate cylinder fields instead of total_amount
        if ($request->bill_type === Bill::TYPE_GAS) {
            $validationRules['cylinder_count'] = 'required|numeric|min:1';
            $validationRules['cylinder_cost'] = 'required|numeric|min:0';
            $validationRules['extra_gas_users'] = 'nullable|array';
            $validationRules['extra_gas_users.*'] = 'exists:members,id';
        } else {
            $validationRules['total_amount'] = 'required|numeric|min:0';
        }

        $this->validate($request, $validationRules);

        $data = $request->only([
            'bill_type',
            'total_amount',
            'bill_date',
            'month',
            'notes',
            'status'
        ]);
        
        // Set applicable members
        if ($request->bill_type === Bill::TYPE_INTERNET) {
            $data['applicable_members'] = 6;
        } else {
            $data['applicable_members'] = 7;
        }

        // Handle gas bill
        if ($request->bill_type === Bill::TYPE_GAS) {
            $data['cylinder_count'] = $request->cylinder_count ?? $bill->cylinder_count ?? 1;
            $data['cylinder_cost'] = $request->cylinder_cost ?? $bill->cylinder_cost ?? 1500;
            $data['extra_gas_users'] = $request->extra_gas_users ?? $bill->extra_gas_users ?? [];
            $data['total_amount'] = $data['cylinder_count'] * $data['cylinder_cost'];
        }

        // Set status if not provided
        if (!isset($data['status'])) {
            $data['status'] = $bill->status ?? 1;
        }

        if ($bill->update($data)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bill updated successfully.'
                ]);
            }
            
            return redirect()->route('admin.bill.index')
                ->with('success', 'Bill updated successfully.');
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to update bill.'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update bill.')
                ->withInput();
        }
    }

    /**
     * View bill details with payment information
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
        
        // Get selected month from request, default to bill's month
        $selectedMonth = $request->input('month', $bill->month);
        
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
        
        // Get all applicable members (Manager and User roles, status = 1)
        // For Internet bill: 6 members, for others: 7 members
        $applicableMembersCount = $bill->bill_type === Bill::TYPE_INTERNET ? 6 : 7;
        $allMembers = Member::whereIn('role_id', [2, 3])
            ->where('status', 1)
            ->orderBy('full_name')
            ->limit($applicableMembersCount)
            ->get();
        
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
        
        // Get available months from payments for this bill type
        $availableMonths = Payment::where('payment_type', $bill->bill_type)
            ->where('status', 1)
            ->select('month')
            ->distinct()
            ->pluck('month')
            ->map(function($m) use ($monthMap) {
                return $monthMap[$m] ?? $m;
            })
            ->unique()
            ->sortBy(function($m) {
                $order = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                $index = array_search($m, $order);
                return $index !== false ? $index : 999;
            })
            ->values();
        
        // Add bill month if not already in list
        if (!$availableMonths->contains($billMonthFull)) {
            $availableMonths->push($billMonthFull);
            $availableMonths = $availableMonths->sortBy(function($m) {
                $order = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                $index = array_search($m, $order);
                return $index !== false ? $index : 999;
            })->values();
        }
        
        return view('admin.bill.view', compact(
            'bill',
            'billTypeName',
            'payments',
            'paymentsByMember',
            'totalPaid',
            'totalPaidCount',
            'uniquePaidMembers',
            'selectedMonth',
            'selectedMonthFull',
            'billMonth',
            'billMonthFull',
            'availableMonths',
            'monthMap'
        ));
    }

    /**
     * Delete bill
     */
    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->update(['status' => 0]);
        
        return redirect()->route('admin.bill.index')
            ->with('success', 'Bill deleted successfully.');
    }
}

