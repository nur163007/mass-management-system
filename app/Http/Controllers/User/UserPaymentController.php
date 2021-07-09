<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use DB;

class UserPaymentController extends Controller
{
    public function index(){
    	$id = session()->get('member_id');
	    	$payments = DB::select("SELECT payments.id,payments.payment_amount,payments.status,payments.date
	        FROM members m
	        INNER JOIN payments ON m.id = payments.member_id where payments.status ='1' and m.id = '$id';");
	        // dd($payments);
        return view('userpanel.payment.user_payment',compact('payments'));
       }

       public function addPayment(){
       	return view('userpanel.payment.add_payment');
       }

       public function storePayment(Request $request){
       	 $this->validate($request,[
            'amount' => 'required',
            'date' => 'required',
        ]);

        $date_convert = date('Y-m-d',strtotime($request->date));
        $month = date('M',strtotime($request->date));

        $payments = new Payment;

        $payments->member_id = session()->get('member_id');;
        $payments->payment_amount = $request->amount;
        $payments->date = $date_convert;
        $payments->month = $month;
        $payments->status = "0";

        if ($payments->save()) {
            return response()->json('success');
    
        }
        else{
            return response()->json("error");
           } 
       }
       public function pendingPayment(){
       	$id = session()->get('member_id');
	    $pending = DB::select("SELECT * FROM payments where status ='0' and member_id = '$id'");
	    // dd($pending);
    	return view('userpanel.payment.pending_payment',compact('pending'));
       }

       public function editPayment($id){
       	// dd($id);
       	$payment = DB::table('payments')->join('members','payments.member_id','members.id')->select('payments.*')->where('payments.id',$id)->first();
       	// dd($payment);
       	return view('userpanel.payment.edit_payment',compact('payment'));
       }

       public function updatePayment(Request $request){
       	$this->validate($request,[
        'amount' => 'required',
        'date' => 'required',
    ]);

     $id = $request->paymentID;
    //  dd($id);
    $date_convert = date('Y-m-d',strtotime($request->date));
    $month = date('M',strtotime($request->date));

    $payments = Payment::findOrFail($id);

    $payments->payment_amount = $request->amount;
    $payments->date = $date_convert;
    $payments->month = $month;

    if ($payments->save()) {
        return response()->json('success');
      }
       else{
             return response()->json("error");
           } 
   }
}
