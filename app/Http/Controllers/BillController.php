<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Member;
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
        $month = $request->input('month', Carbon::now()->isoFormat('MMM'));
        $bills = Bill::where('month', $month)
            ->where('status', 1)
            ->get();
        
        return view('admin.bill.index', compact('bills', 'month'));
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

