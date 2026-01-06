<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FoodAdvance;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodAdvanceController extends Controller
{
    public function index(Request $request){
        $id = session()->get('member_id');
        $query = DB::table('food_advances')
            ->join('members', 'food_advances.member_id', '=', 'members.id')
            ->select('food_advances.*', 'members.full_name')
            ->where('food_advances.member_id', $id);
        
        // Filter by month if provided
        if($request->has('month') && $request->month != ''){
            $query->where('food_advances.month', $request->month);
        }
        
        $advances = $query->orderBy('food_advances.date', 'DESC')->get();
        
        // Get unique months for dropdown (only for this user)
        $months = DB::table('food_advances')
            ->select('month')
            ->where('member_id', $id)
            ->distinct()
            ->orderByRaw("FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->pluck('month');
        
        $selectedMonth = $request->month ?? '';
        
        return view('userpanel.foodAdvance.index', compact('advances', 'months', 'selectedMonth'));
    }

    public function create(){
        return view('userpanel.foodAdvance.create');
    }

    public function store(Request $request){
        $this->validate($request, [
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
        ]);

        $date_convert = date('Y-m-d', strtotime($request->date));
        $month = date('F', strtotime($request->date));

        $advance = new FoodAdvance;
        $advance->member_id = session()->get('member_id');
        $advance->amount = $request->amount;
        $advance->date = $date_convert;
        $advance->month = $month;
        $advance->status = 0; // Pending
        $advance->notes = $request->notes ?? null;

        if($advance->save()){
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function edit($id){
        $advance = FoodAdvance::where('id', $id)
            ->where('member_id', session()->get('member_id'))
            ->where('status', 0) // Only allow editing if status is 0 (pending)
            ->first();

        if(!$advance){
            return redirect()->route('user.foodAdvance.index')->with('error', 'Food advance not found or already approved.');
        }

        return view('userpanel.foodAdvance.edit', compact('advance'));
    }

    public function update(Request $request){
        $this->validate($request, [
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
        ]);

        $id = $request->id;
        $advance = FoodAdvance::findOrFail($id);

        // Verify that this advance belongs to the logged-in user and is pending
        if($advance->member_id != session()->get('member_id') || $advance->status != 0){
            return response()->json('error');
        }

        $date_convert = date('Y-m-d', strtotime($request->date));
        $month = date('F', strtotime($request->date));

        $advance->amount = $request->amount;
        $advance->date = $date_convert;
        $advance->month = $month;
        $advance->notes = $request->notes ?? null;

        if($advance->save()){
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function view($id){
        $advance = DB::table('food_advances')
            ->join('members', 'food_advances.member_id', '=', 'members.id')
            ->select('food_advances.*', 'members.full_name')
            ->where('food_advances.id', $id)
            ->where('food_advances.member_id', session()->get('member_id'))
            ->first();
        
        if(!$advance){
            return redirect()->route('user.foodAdvance.index')->with('error', 'Food advance not found.');
        }

        return view('userpanel.foodAdvance.view', compact('advance'));
    }
}
