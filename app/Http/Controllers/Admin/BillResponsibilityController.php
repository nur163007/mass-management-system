<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillTypeResponsibility;
use App\Models\Member;

class BillResponsibilityController extends Controller
{
    public function index()
    {
        $responsibilities = BillTypeResponsibility::with('member')->get();
        $billTypes = BillTypeResponsibility::getBillTypes();
        $members = Member::whereIn('role_id', [2, 3])->where('status', 1)->orderBy('full_name')->get();
        
        return view('admin.billResponsibility.index', compact('responsibilities', 'billTypes', 'members'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'member_id' => 'required|exists:members,id',
            'bill_type' => 'required|in:water,internet,electricity,gas,bua_moyla,room_rent,room_advance,food_advance',
        ]);

        // Check if this bill type already has a responsible member
        $existing = BillTypeResponsibility::where('bill_type', $request->bill_type)->first();
        
        if ($existing) {
            return redirect()->back()->with('error', 'This bill type already has a responsible member. Please update the existing one.');
        }

        BillTypeResponsibility::create([
            'member_id' => $request->member_id,
            'bill_type' => $request->bill_type,
        ]);

        return redirect()->back()->with('success', 'Bill responsibility assigned successfully.');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'member_id' => 'required|exists:members,id',
            'bill_type' => 'required|in:water,internet,electricity,gas,bua_moyla,room_rent,room_advance,food_advance',
        ]);

        $responsibility = BillTypeResponsibility::findOrFail($id);

        // Check if this bill type already has another responsible member
        $existing = BillTypeResponsibility::where('bill_type', $request->bill_type)
            ->where('id', '!=', $id)
            ->first();
        
        if ($existing) {
            return redirect()->back()->with('error', 'This bill type already has a responsible member.');
        }

        $responsibility->update([
            'member_id' => $request->member_id,
            'bill_type' => $request->bill_type,
        ]);

        return redirect()->back()->with('success', 'Bill responsibility updated successfully.');
    }

    public function destroy($id)
    {
        $responsibility = BillTypeResponsibility::findOrFail($id);
        $responsibility->delete();

        return redirect()->back()->with('success', 'Bill responsibility removed successfully.');
    }
}
