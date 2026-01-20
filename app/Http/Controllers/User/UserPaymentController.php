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
    	
    	// Month format conversion map
    	$monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        
        // Convert month to full form if abbreviated
        $monthFull = $monthMap[$month] ?? $month;
        $reverseMap = array_flip($monthMap);
        $monthAbbr = $reverseMap[$monthFull] ?? null;
    	
    	// Get all individual payments with dates for the selected month (check both formats)
    	$payments = Payment::where('member_id', $id)
    		->where(function($query) use ($month, $monthFull, $monthAbbr) {
    			$query->where('month', $month);
    			if ($monthFull != $month) {
    				$query->orWhere('month', $monthFull);
    			}
    			if ($monthAbbr && $monthAbbr != $month && $monthAbbr != $monthFull) {
    				$query->orWhere('month', $monthAbbr);
    			}
    		})
    		->where('status', 1)
    		->orderBy('date', 'DESC')
    		->orderBy('payment_type', 'ASC')
    		->get();
    	
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
    			'amount' => $payment->payment_amount,
    			'date' => date('d M Y', strtotime($payment->date)),
    			'notes' => $payment->notes ?? ''
    		];
    		$grandTotal += $payment->payment_amount;
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
