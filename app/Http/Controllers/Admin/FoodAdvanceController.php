<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodAdvance;
use App\Models\Member;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodAdvanceController extends Controller
{
    public function index(Request $request){
        $query = DB::table('food_advances')
            ->join('members', 'food_advances.member_id', '=', 'members.id')
            ->select('food_advances.*', 'members.full_name');
        
        // Filter by month if provided
        if($request->has('month') && $request->month != ''){
            $query->where('food_advances.month', $request->month);
        }
        
        // Filter by member if provided
        if($request->has('member_id') && $request->member_id != ''){
            $query->where('food_advances.member_id', $request->member_id);
        }
        
        $advances = $query->orderBy('food_advances.date', 'DESC')->get();
        
        // Get unique months for dropdown
        $months = DB::table('food_advances')
            ->select('month')
            ->distinct()
            ->orderByRaw("FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->pluck('month');
        
        // Get members for dropdown (exclude Super Admin)
        $members = Member::whereIn('role_id', [2, 3])->orderBy('full_name')->get();
        
        $selectedMonth = $request->month ?? '';
        $selectedMemberId = $request->member_id ?? '';
        
        return view('admin.foodAdvance.index', compact('advances', 'months', 'members', 'selectedMonth', 'selectedMemberId'));
    }

    public function create(){
        // Exclude Super Admin (role_id = 1), only include Manager (2) and User (3)
        $members = Member::whereIn('role_id', [2, 3])->get();
        return view('admin.foodAdvance.create', compact('members'));
    }

    public function store(Request $request){
        $this->validate($request, [
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
        ]);

        $date_convert = date('Y-m-d', strtotime($request->date));
        $month = date('F', strtotime($request->date));

        $advance = new FoodAdvance;
        $advance->member_id = $request->member_id;
        $advance->amount = $request->amount;
        $advance->date = $date_convert;
        $advance->month = $month;
        $advance->status = 1; // Directly approved for admin
        $advance->notes = $request->notes ?? null;

        if($advance->save()){
            // Create payment entry immediately (admin advances are directly approved)
            $payment = new Payment;
            $payment->member_id = $request->member_id;
            $payment->payment_amount = $request->amount;
            $payment->payment_type = 'food_advance';
            $payment->date = $date_convert;
            $payment->month = $month;
            $payment->status = "1";
            $payment->notes = "Food advance added by admin";
            $payment->save();

            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function approve($id){
        $advance = FoodAdvance::findOrFail($id);
        
        if($advance->status == 1){
            return response()->json(['message' => 'Already approved'], 400);
        }

        $advance->status = 1;
        
        if($advance->save()){
            // Create payment entry
            $payment = new Payment;
            $payment->member_id = $advance->member_id;
            $payment->payment_amount = $advance->amount;
            $payment->payment_type = 'food_advance';
            $payment->date = $advance->date;
            $payment->month = $advance->month;
            $payment->status = "1";
            $payment->notes = "Food advance approved from request";
            $payment->save();

            return response()->json(['message' => 'Success']);
        } else {
            return response()->json(['message' => 'Error'], 500);
        }
    }

    public function edit($id){
        $advance = FoodAdvance::findOrFail($id);
        // Exclude Super Admin (role_id = 1), only include Manager (2) and User (3)
        $members = Member::whereIn('role_id', [2, 3])->get();
        return view('admin.foodAdvance.edit', compact('advance', 'members'));
    }

    public function update(Request $request){
        $this->validate($request, [
            'id' => 'required|exists:food_advances,id',
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
        ]);

        $advance = FoodAdvance::findOrFail($request->id);
        $date_convert = date('Y-m-d', strtotime($request->date));
        $month = date('F', strtotime($request->date));

        // If status was approved and we're updating, we might need to update payment too
        $wasApproved = $advance->status == 1;
        $oldAmount = $advance->amount;
        $oldMemberId = $advance->member_id;

        $advance->member_id = $request->member_id;
        $advance->amount = $request->amount;
        $advance->date = $date_convert;
        $advance->month = $month;
        $advance->notes = $request->notes ?? null;

        if($advance->save()){
            // If it was approved, update the payment entry
            if($wasApproved){
                $payment = Payment::where('member_id', $oldMemberId)
                    ->where('payment_type', 'food_advance')
                    ->where('payment_amount', $oldAmount)
                    ->where('date', $advance->date)
                    ->first();
                
                if($payment){
                    $payment->member_id = $request->member_id;
                    $payment->payment_amount = $request->amount;
                    $payment->date = $date_convert;
                    $payment->month = $month;
                    $payment->save();
                }
            }

            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function view($id){
        $advance = DB::table('food_advances')
            ->join('members', 'food_advances.member_id', '=', 'members.id')
            ->select('food_advances.*', 'members.full_name', 'members.phone_no', 'members.email')
            ->where('food_advances.id', $id)
            ->first();
        
        if(!$advance){
            return redirect()->route('admin.foodAdvance.index')->with('error', 'Food advance not found.');
        }

        return view('admin.foodAdvance.view', compact('advance'));
    }
}
