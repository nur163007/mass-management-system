<?php

namespace App\Http\Controllers;

use App\Models\ServiceCharge;
use App\Models\ServiceExpense;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceChargeController extends Controller
{
    /**
     * View service charges
     */
    public function index()
    {
        $serviceCharges = ServiceCharge::with('member')->get();
        $totalCollected = ServiceCharge::sum('amount');
        $totalSpent = ServiceExpense::where('status', 1)->sum('amount');
        $balance = $totalCollected - $totalSpent;
        
        // Get Manager (2) and User (3) members only, exclude Super Admin (1)
        $members = Member::whereIn('role_id', [2, 3])
            ->where('status', 1)
            ->get();
        
        return view('admin.servicecharge.index', compact('serviceCharges', 'totalCollected', 'totalSpent', 'balance', 'members'));
    }

    /**
     * Store service charge (when new member joins)
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        // Check if member already paid service charge
        $existing = ServiceCharge::where('member_id', $request->member_id)->first();
        if ($existing) {
            return redirect()->back()
                ->with('error', 'Service charge already collected from this member.');
        }

        ServiceCharge::create($request->all());
        
        return redirect()->route('admin.servicecharge.index')
            ->with('success', 'Service charge recorded successfully.');
    }

    /**
     * View service expenses
     */
    public function expenses(Request $request)
    {
        $month = $request->input('month', Carbon::now()->isoFormat('MMM'));
        $expenses = ServiceExpense::where('month', $month)
            ->where('status', 1)
            ->get();
        
        $totalCollected = ServiceCharge::sum('amount');
        $totalSpent = ServiceExpense::where('status', 1)->sum('amount');
        $balance = $totalCollected - $totalSpent;
        
        return view('admin.servicecharge.expenses', compact('expenses', 'month', 'totalCollected', 'totalSpent', 'balance'));
    }

    /**
     * Store service expense
     */
    public function storeExpense(Request $request)
    {
        $this->validate($request, [
            'expense_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'month' => 'required',
            'description' => 'nullable|string',
        ]);

        ServiceExpense::create($request->all());
        
        return redirect()->route('admin.servicecharge.expenses')
            ->with('success', 'Service expense recorded successfully.');
    }

    /**
     * Delete service expense
     */
    public function deleteExpense($id)
    {
        $expense = ServiceExpense::findOrFail($id);
        $expense->update(['status' => 0]);
        
        return redirect()->route('admin.servicecharge.expenses')
            ->with('success', 'Service expense deleted successfully.');
    }
}

