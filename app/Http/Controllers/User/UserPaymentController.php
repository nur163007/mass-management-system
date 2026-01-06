<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class UserPaymentController extends Controller
{
    public function index(){
    	$id = session()->get('member_id');
	    	$payments = DB::select("SELECT payments.month, SUM(payments.payment_amount) as total_amount
	        FROM members m
	        INNER JOIN payments ON m.id = payments.member_id 
	        WHERE payments.status ='1' and m.id = '$id'
	        GROUP BY payments.month
	        ORDER BY payments.month DESC");
	        // dd($payments);
        return view('userpanel.payment.user_payment',compact('payments'));
       }

    public function getPaymentDetails($month){
    	$id = session()->get('member_id');
    	$payments = DB::select("SELECT payments.payment_type, SUM(payments.payment_amount) as total_amount
	        FROM payments
	        WHERE payments.status ='1' and payments.member_id = '$id' and payments.month = '$month'
	        GROUP BY payments.payment_type
	        ORDER BY payments.payment_type");
    	
    	$paymentTypes = [
            'food_advance' => 'Food Advance',
            'room_rent' => 'Room Rent',
            'room_advance' => 'Room Advance',
            'water' => 'Water Bill',
            'internet' => 'Internet Bill',
            'electricity' => 'Electricity Bill',
            'gas' => 'Gas Bill',
            'bua_moyla' => 'Bua & Moyla Bill',
            'other' => 'Other'
        ];
    	
    	$formattedPayments = [];
    	$grandTotal = 0;
    	
    	foreach($payments as $payment) {
    		$typeName = $paymentTypes[$payment->payment_type] ?? ucfirst(str_replace('_', ' ', $payment->payment_type));
    		$formattedPayments[] = [
    			'type' => $typeName,
    			'amount' => $payment->total_amount
    		];
    		$grandTotal += $payment->total_amount;
    	}
    	
    	return response()->json([
    		'success' => true,
    		'month' => $month,
    		'payments' => $formattedPayments,
    		'total' => $grandTotal
    	]);
    }

       public function addPayment(){
       	return view('userpanel.payment.add_payment');
       }

       public function storePayment(Request $request){
       	 $this->validate($request,[
            'payment_type' => 'required|in:food_advance,room_rent,room_advance,water,internet,electricity,gas,bua_moyla',
            'amount' => 'required',
            'date' => 'required',
        ]);

        $date_convert = date('Y-m-d',strtotime($request->date));
        $month = date('F',strtotime($request->date));

        $payments = new Payment;

        $payments->member_id = session()->get('member_id');
        $payments->payment_amount = $request->amount;
        $payments->payment_type = $request->payment_type;
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

}
