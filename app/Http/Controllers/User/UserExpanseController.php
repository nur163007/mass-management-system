<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Expanse;
use App\Models\ExpanseDetails;
use Illuminate\Support\Facades\DB;

class UserExpanseController extends Controller
{
    public function index(){

    	$id = session()->get('member_id');
	    	$expanse = DB::select("SELECT m.id,SUM(expanses.total_amount) as total, expanses.date,expanses.status,expanses.invoice_no, expanses.id as expanse_id,
                (SELECT id FROM expanse_details WHERE invoice_no = expanses.invoice_no LIMIT 1) as first_detail_id
	        FROM members m
	        INNER JOIN expanses ON m.id = expanses.member_id where m.id = '$id' GROUP BY expanses.date, expanses.invoice_no, expanses.status, expanses.id ORDER BY expanses.date DESC");
// dd($expanse);
        return view('userpanel.expanse.user_expanse',compact('expanse'));
    }

    public function editExpanse($id){
        $expanses=DB::table('expanse_details')
        ->join('food_items','expanse_details.item_name_id','food_items.id')
        ->join('expanses','expanse_details.invoice_no','expanses.invoice_no')
        ->select('expanses.category_id','expanses.total_amount','expanses.status','expanses.member_id','food_items.item_name','expanse_details.*')
        ->where('expanse_details.id',$id)
        ->where('expanses.member_id',session()->get('member_id'))
        ->where('expanses.status','0')
        ->first();

        if(!$expanses){
            return redirect()->route('user.viewExpanse')->with('error','Expanse not found or already approved.');
        }

        $items= FoodItem::all();
        return view('userpanel.expanse.edit_expanse',compact('expanses','items'));
    }

    public function updateExpanse(Request $request){
        $this->validate($request,[
            'item_name_id' => 'required',
            'weight' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        $id = $request->exDetailID;
        $date_convert = date('Y-m-d',strtotime($request->date));
        $month = date('F',strtotime($request->date));
        $expanses = ExpanseDetails::findOrFail($id);

        // Verify that this expanse belongs to the logged-in user and is pending
        $expanse_check = DB::table('expanses')
            ->where('invoice_no',$expanses->invoice_no)
            ->where('member_id',session()->get('member_id'))
            ->where('status','0')
            ->first();

        if(!$expanse_check){
            return response()->json("error");
        }

        $invoice = $expanses->invoice_no;
        $prev_amount = $expanses->amount;

        $ex_all = DB::table('expanses')->where('invoice_no',$invoice)->first();
        $ex_id = $ex_all->id;
        $prev_total = $ex_all->total_amount;
        $ex_update = Expanse::findOrFail($ex_id);

        $update_total = 0;
        try {
            if($prev_amount == $request->amount){
                $update_total += $prev_total+0;
            }
            elseif($prev_amount > $request->amount){
                $update_total += $prev_total - ($prev_amount - $request->amount);
            }
            elseif($prev_amount < $request->amount){
                $update_total += $prev_total + ($request->amount - $prev_amount);
            }

            $expanses->item_name_id = $request->item_name_id;
            $expanses->weight = $request->weight;
            $expanses->amount = $request->amount;
            $expanses->date = $date_convert;

            $ex_update->total_amount = $update_total;
            $ex_update->date = $date_convert;
            $ex_update->month = $month;

            $expanses->save();
            $ex_update->save();
            return response()->json("success");
        } catch (\Throwable $th) {
            return response()->json("error");
        }
    }

    public function detailsExpanse($invoice,$id){
		$all_details=DB::table('expanse_details')
        ->join('food_items','expanse_details.item_name_id','food_items.id')
        ->join('expanses','expanse_details.invoice_no','expanses.invoice_no')
        ->select('food_items.item_name','food_items.item_description','expanses.invoice_no','expanses.total_amount','expanses.status','expanse_details.*')
        ->where('expanse_details.invoice_no',$invoice)
        ->where('expanses.member_id',$id)
        ->get();  
        
        $expanse_status = DB::table('expanses')
            ->where('invoice_no',$invoice)
            ->where('member_id',$id)
            ->select('expanses.*')
            ->first();

        $members = DB::table('expanse_details')
        ->join('members','expanse_details.member_id','members.id')
        ->join('expanses','expanse_details.invoice_no','expanses.invoice_no')
        ->select('members.full_name','members.phone_no','members.photo','expanses.total_amount','expanse_details.date')
        ->where('expanse_details.invoice_no',$invoice)
        ->where('expanses.member_id',$id)
        ->first();
// dd($all_details);
    	return view('userpanel.expanse.details_expanse',compact('all_details','expanse_status','members'));
    }

    public function addExpanse(){
         $items= FoodItem::all();
    	return view('userpanel.expanse.add_expanse',compact('items'));
    }

    public function storeExpanse(Request $request){
    	$this->validate($request,[
            'cost_bearer' => 'required|in:food_advance,user',
            'item_name_id' => 'required',
            'weight' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        $invoice =rand(100000,999999);
       $date_convert = date('Y-m-d',strtotime($request->date));
       $total = 0;
      
        $item_name_size = sizeof($request->item_name_id);

        try {
            if($item_name_size>0){

                for($i = 0; $i < $item_name_size; $i++){
                    $expanses_details = new ExpanseDetails;
    
                    $expanses_details->invoice_no = $invoice;
                    $expanses_details->member_id = session()->get('member_id');
                    // $expanses->category_id = $request->category_id;
                    $expanses_details->item_name_id = $request->item_name_id[$i];
                    $expanses_details->weight = $request->weight[$i];
                    $expanses_details->amount = $request->amount[$i];
                    $expanses_details->date = $date_convert; 
                    $total += $request->amount[$i];
                 
                    $expanses_details->save();
                }
    
                $month = date('F',strtotime($request->date));
                $expanses = new Expanse;
                $expanses->invoice_no = $invoice;
                $expanses->member_id = session()->get('member_id');
                $expanses->category_id = $request->category_id ?? null;
                $expanses->total_amount= $total;
                $expanses->date = $date_convert;
                $expanses->month = $month;
                $expanses->status = "0";
                $expanses->cost_bearer = $request->cost_bearer;

                $expanses->save();
    
                return response()->json('success');
    
             }
             else{
                 return response()->json("error");
                } 
        } catch (\Throwable $th) {
            //throw $th;
            // dd($th);
        }
    }

    public function pendingExpanse(){
    	$id = session()->get('member_id');
	    $pending = DB::select("SELECT expanses.*, (SELECT id FROM expanse_details WHERE invoice_no = expanses.invoice_no LIMIT 1) as first_detail_id FROM expanses where status ='0' and member_id = '$id'");
	    // dd($pending);
    	return view('userpanel.expanse.pending_expanse',compact('pending'));
    }
}
