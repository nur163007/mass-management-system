<?php

namespace App\Http\Controllers;
use App\Models\Member;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function addPayment(){
        $members = Member::all();
        return view('admin.payment.addPayment',compact('members'));
    }

    public function storePayment(Request $request){
        // dd($request);
        $this->validate($request,[
            'member_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        $date_convert = date('Y-m-d',strtotime($request->date));

        $payments = new Payment;

        $payments->member_id = $request->member_id;
        $payments->payment_amount = $request->amount;
        $payments->date = $date_convert;

        if ($payments->save()) {
            return response()->json('success');
    
        }
        else{
            return response()->json("error");
           } 
    }

    public function viewPayment(){
        $payments = DB::table('payments')
        ->join('members','payments.member_id','members.id')
        ->select('payments.*','members.full_name')
        ->get();
        // dd($payments);
        return view('admin.payment.viewPayment',compact('payments'));
    }

    public function deletePayment($id){
        // dd($id);
        $payments = Payment::findOrFail($id);
         
        if($payments){
            $payments->delete();
            return redirect()->back()->with('success','Payment successfully deleted.');
        }else{
            return redirect()->back()->with('error','Something Error Found !, Please try again.');
        }
    }

    public function editPayment($id){
        // dd($id);
        $payments=DB::table('payments')
        ->join('members','payments.member_id','members.id')
        ->select('members.full_name','payments.*')
        ->where('payments.id',$id)
        ->first();
        // dd($payments);

        $members = Member::all();
        return view('admin.payment.editPayment',compact('payments','members'));
    }


    public function updatePayment(Request $request){
    // dd($request);
    $this->validate($request,[
        'member_id' => 'required',
        'amount' => 'required',
        'date' => 'required',
    ]);

     $id = $request->paymentID;
    //  dd($id);
    $date_convert = date('Y-m-d',strtotime($request->date));

    $payments = Payment::findOrFail($id);

    $payments->member_id = $request->member_id;
    $payments->payment_amount = $request->amount;
    $payments->date = $date_convert;

    if ($payments->save()) {
        return response()->json('success');

    }
    else{
        return response()->json("error");
       } 


    }
}
