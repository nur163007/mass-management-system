<?php

namespace App\Http\Controllers;

use App\Models\MemberExtraPayment;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MemberExtraPaymentController extends Controller
{
    /**
     * View extra payments
     */
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->isoFormat('MMM'));
        $payments = MemberExtraPayment::with('member')
            ->where('month', $month)
            ->where('status', 1)
            ->get();
        
        return view('admin.extra_payment.index', compact('payments', 'month'));
    }

    /**
     * Show form to add extra payment
     */
    public function create()
    {
        // Get Manager (2) and User (3) members only, exclude Super Admin (1)
        $members = Member::whereIn('role_id', [2, 3])->where('status', 1)->get();
        return view('admin.extra_payment.create', compact('members'));
    }

    /**
     * Store extra payment
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'month' => 'required',
            'rent_reduction' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Also record in payments table as other payment
        \App\Models\Payment::create([
            'member_id' => $request->member_id,
            'payment_amount' => $request->amount,
            'payment_type' => 'other',
            'date' => $request->payment_date,
            'month' => $request->month,
            'status' => 1,
            'notes' => 'Extra payment - ' . $request->description,
        ]);

        MemberExtraPayment::create($request->all());
        
        return redirect()->route('admin.extra_payment.index')
            ->with('success', 'Extra payment recorded successfully.');
    }

    /**
     * Edit extra payment
     */
    public function edit($id)
    {
        $payment = MemberExtraPayment::findOrFail($id);
        // Get Manager (2) and User (3) members only, exclude Super Admin (1)
        $members = Member::whereIn('role_id', [2, 3])->where('status', 1)->get();
        return view('admin.extra_payment.edit', compact('payment', 'members'));
    }

    /**
     * Update extra payment
     */
    public function update(Request $request, $id)
    {
        $payment = MemberExtraPayment::findOrFail($id);
        
        $this->validate($request, [
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'month' => 'required',
            'rent_reduction' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $payment->update($request->all());
        
        return redirect()->route('admin.extra_payment.index')
            ->with('success', 'Extra payment updated successfully.');
    }

    /**
     * Delete extra payment
     */
    public function destroy($id)
    {
        $payment = MemberExtraPayment::findOrFail($id);
        $payment->update(['status' => 0]);
        
        return redirect()->route('admin.extra_payment.index')
            ->with('success', 'Extra payment deleted successfully.');
    }
}

