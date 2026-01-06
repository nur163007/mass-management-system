<?php

namespace App\Http\Controllers;
use App\Models\ExpanseDetails;
use App\Models\Expanse;
use App\Models\Member;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
// use Illuminate\Support\Facades\Str;

class ExpanseController extends Controller
{
    public function addExpanse(){
        // Exclude Super Admin (role_id = 1), only include Manager (2) and User (3)
        $members = Member::whereIn('role_id', [2, 3])->get();
        $items= FoodItem::all();
        return view('admin.expanse.addExpanse',compact('members','items'));
    }

    public function storeExpanse(Request $request){
        // dd($request);

        // $test = implode(',',$request->item_name_id);
        // dd($test);
        $this->validate($request,[
            'member_id' => 'required',
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
                    $expanses_details->member_id = $request->member_id;
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
                $expanses->member_id = $request->member_id;
                $expanses->category_id = $request->category_id ?? null;
                $expanses->total_amount= $total;
                $expanses->date = $date_convert;
                $expanses->month = $month;
                $expanses->status = "1";
                $expanses->cost_bearer = $request->cost_bearer;

                $expanses->save();

                // If cost_bearer is 'user', create payment entry immediately (admin expanses are directly approved)
                if($request->cost_bearer == 'user'){
                    $payment = new Payment;
                    $payment->member_id = $request->member_id;
                    $payment->payment_amount = $total;
                    $payment->payment_type = 'food_advance';
                    $payment->date = $date_convert;
                    $payment->month = $month;
                    $payment->status = "1";
                    $payment->notes = "Auto-generated from admin expanse invoice: " . $invoice;
                    $payment->save();
                }

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

 public function onChange(Request $request)
 {
    $id = $request->id;
    $items =DB::table('food_items')->select('food_items.*')->where('food_category_id',$id)->get();
    return response()->json($items);
}


public function viewExpanse(){
        // $bazars = DB::table('expanses')
        // ->join('members','expanses.member_id','members.id')
        // // ->join('food_categories','expanses.category_id','food_categories.id')
        // // ->join('food_items','expanses.item_name_id','food_items.id')
        // ->select('expanses.date','members.full_name',SUM('expanses.total_amount'))
        // ->groupBy('expanses.date')
        // ->get();

        // dd("ok");
 $bazars = DB::select("SELECT ex.id,ex.invoice_no,ex.total_amount as total, ex.date as expanse_date,ex.status,ex.created_at,m.full_name
    FROM expanses ex
    INNER JOIN members m ON ex.member_id = m.id
    ORDER BY ex.created_at DESC, ex.id DESC");
        // dd($bazars);
 return view('admin.expanse.viewExpanse',compact('bazars'));
}

public function expanseStatus($id, $status){
        // dd($id);
    $active = Expanse::findOrFail($id);
    $prev_status = $active->status;
    $active->status= $status; 

    if($active->save()){
        // If status changed from 0 (pending) to 1 (approved) and cost_bearer is 'user', create payment entry
        if($prev_status == 0 && $status == 1 && $active->cost_bearer == 'user'){
            $payment = new Payment;
            $payment->member_id = $active->member_id;
            $payment->payment_amount = $active->total_amount;
            $payment->payment_type = 'food_advance';
            $payment->date = $active->date;
            $payment->month = $active->month;
            $payment->status = "1";
            $payment->notes = "Auto-generated from expanse invoice: " . $active->invoice_no;
            $payment->save();
        }
        
        return response()->json(['message'=> 'Success']);
    }
}

public function deleteExpanse($invoice_number){

   $expanses = Expanse::where('invoice_no',$invoice_number)->first();
   $expanses_details = ExpanseDetails::where('invoice_no',$invoice_number)->get();
        //$ex =  DB::table('expanses')->where('invoice_no',$invoice_number)->get();

   if($expanses && $expanses_details){
    $expanses->delete();
            //$expanses_details->delete();
    foreach($expanses_details as $ex_details){
        $ex_details->delete();
    }
    return redirect()->back()->with('success','Expanse successfully deleted.');
}else{
    return redirect()->back()->with('error','Something Error Found !, Please try again.');
}
        // dd($expanses);
}

public function editExpanse($id){
        // $id = Expanse::findOrFail($id);
        // dd($id);
    $expanses=DB::table('expanse_details')
    ->join('members','expanse_details.member_id','members.id')

    ->join('food_items','expanse_details.item_name_id','food_items.id')
    ->join('expanses','expanse_details.invoice_no','expanses.invoice_no')
    ->select('expanses.category_id','expanses.total_amount','members.full_name','food_items.item_name','food_items.item_description','food_items.item_photo','expanse_details.*')
    ->where('expanse_details.id',$id)
    ->first();
        // dd($expanses);

    // Exclude Super Admin (role_id = 1), only include Manager (2) and User (3)
    $members = Member::whereIn('role_id', [2, 3])->get();
    $categories= Category::all();
    $items= FoodItem::all();
    return view('admin.expanse.editExpanse',compact('expanses','members','categories','items'));
}

public function updateExpanse(Request $request){
        // dd($request);

    $id = $request->exDetailID;
        // dd($id);
    $date_convert = date('y-m-d',strtotime($request->date));
    $month = date('F',strtotime($request->date));
    $expanses = ExpanseDetails::findOrFail($id);

    $invoice = $expanses->invoice_no;
    $prev_amount = $expanses->amount;
        // dd($invoice);

    $ex_all = DB::table('expanses')->where('invoice_no',$invoice)->first();
        // dd($ex_all);
    $ex_id = $ex_all->id;
    $prev_total = $ex_all->total_amount;
    $ex_update = Expanse::findOrFail($ex_id);
// dd($ex_update);
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

        // $expanses->member_id = $request->member_id;
        // $expanses->category_id = $request->category_id;
        $expanses->item_name_id = $request->item_name_id;
        $expanses->weight = $request->weight;
        $expanses->amount = $request->amount;
        $expanses->date = $date_convert;

        // dd($expanses);
        


        $ex_update->total_amount = $update_total;
        $ex_update->date = $date_convert;
        $ex_update->month = $month;

        $expanses->save();
        $ex_update->save();
        return response()->json("success");
    } catch (\Throwable $th) {
            //throw $th;
            // dd($th);
    }
}

public function detailExpanse($invoice_no){
        // dd($invoice_no);
    $all_details=DB::table('expanse_details')
    ->join('food_items','expanse_details.item_name_id','food_items.id')
    ->join('expanses','expanse_details.invoice_no','expanses.invoice_no')
    ->select('food_items.item_name','food_items.item_description','expanses.invoice_no','expanses.total_amount','expanse_details.*')
    ->where('expanse_details.invoice_no',$invoice_no)
    ->get();  
// dd($all_details);
    $members = DB::table('expanse_details')
    ->join('members','expanse_details.member_id','members.id')
    ->join('expanses','expanse_details.invoice_no','expanses.invoice_no')
    ->select('members.full_name','members.phone_no','members.photo','expanses.total_amount','expanse_details.date')
    ->where('expanse_details.invoice_no',$invoice_no)
    ->first();
    //    dd($members);
        // dd($all_details);
    return view('admin.expanse.detailExpanse',compact('all_details','members')); 
}

public function downloadPdf(){
  $bazars = DB::select("SELECT ex.id,ex.invoice_no,ex.total_amount as total, ex.date as expanse_date,ex.status,m.full_name FROM expanses ex INNER JOIN members m ON ex.member_id = m.id");

  $pdf = PDF::loadView('admin.expanse.expansePdf',compact('bazars'));
  return $pdf->download('bazars.pdf');
}
}
