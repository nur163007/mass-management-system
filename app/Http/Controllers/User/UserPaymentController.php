<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Bill;
use App\Models\BillTypeResponsibility;
use App\Services\BillService;
use App\Services\RoomRentService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserPaymentController extends Controller
{
    protected $billService;
    protected $roomRentService;

    public function __construct(BillService $billService, RoomRentService $roomRentService)
    {
        $this->billService = $billService;
        $this->roomRentService = $roomRentService;
    }
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
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $memberId = session()->get('member_id');
        if (!$memberId) {
            return response()->json(['error' => 'User not logged in'], 401);
        }

        $date_convert = date('Y-m-d',strtotime($request->date));
        $month = date('F',strtotime($request->date)); // Full month name (January, February, etc.)

        // Month format conversion map
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        $reverseMap = array_flip($monthMap);
        $monthAbbr = $reverseMap[$month] ?? null;

        // Check if payment already exists for this month and payment type (prevent duplicate)
        $existingPayment = Payment::where('member_id', $memberId)
            ->where('payment_type', $request->payment_type)
            ->where(function($query) use ($month, $monthAbbr) {
                $query->where('month', $month);
                if ($monthAbbr) {
                    $query->orWhere('month', $monthAbbr);
                }
            })
            ->first();

        if ($existingPayment) {
            return response()->json([
                'error' => 'Payment already exists for this month and payment type. Duplicate entry not allowed.',
                'existing_payment' => [
                    'id' => $existingPayment->id,
                    'amount' => $existingPayment->payment_amount,
                    'status' => $existingPayment->status,
                    'date' => $existingPayment->date
                ]
            ], 422);
        }

        $payments = new Payment;

        $payments->member_id = $memberId;
        $payments->payment_amount = $request->amount;
        $payments->payment_type = $request->payment_type;
        $payments->date = $date_convert;
        $payments->month = $month;
        
        // Check if the paying member is responsible for this bill type - auto approve
        $responsibility = BillTypeResponsibility::where('bill_type', $request->payment_type)
            ->where('member_id', $memberId)
            ->first();
        
        // Auto-approve if responsible person pays for their own bill
        $payments->status = $responsibility ? "1" : "0";

        if ($payments->save()) {
            return response()->json([
                'success' => true,
                'status' => $payments->status,
                'message' => $responsibility ? 'Payment automatically approved (you are responsible for this bill type).' : 'Payment submitted successfully. Waiting for approval.'
            ]);
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

    /**
     * Get bill amount for the logged-in user based on payment type
     * Also checks if payment already exists for current month (prevent duplicate)
     */
    public function getBillAmount(Request $request)
    {
        $memberId = session()->get('member_id');
        if (!$memberId) {
            return response()->json([
                'success' => false,
                'message' => 'User not logged in'
            ]);
        }

        $this->validate($request, [
            'payment_type' => 'required|in:food_advance,room_rent,room_advance,water,internet,electricity,gas,bua_moyla',
        ]);

        $paymentType = $request->payment_type;
        $currentMonth = Carbon::now()->format('F'); // Full month name (January, February, etc.)

        // Month format conversion map
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        $reverseMap = array_flip($monthMap);
        $currentMonthAbbr = $reverseMap[$currentMonth] ?? null;

        // Check if payment already exists for current month (prevent duplicate)
        $existingPayment = Payment::where('member_id', $memberId)
            ->where('payment_type', $paymentType)
            ->where(function($query) use ($currentMonth, $currentMonthAbbr) {
                $query->where('month', $currentMonth);
                if ($currentMonthAbbr) {
                    $query->orWhere('month', $currentMonthAbbr);
                }
            })
            ->first();

        $paymentExists = $existingPayment ? true : false;
        $existingPaymentStatus = $existingPayment ? $existingPayment->status : null;
        $existingPaymentAmount = $existingPayment ? $existingPayment->payment_amount : 0;

        // Handle food advance payment
        if ($paymentType === 'food_advance') {
            // Food advance doesn't have a bill, so just check for existing payment
            return response()->json([
                'success' => true,
                'amount' => 0,
                'total_bill' => 0,
                'paid' => $existingPaymentAmount,
                'due' => 0,
                'payment_exists' => $paymentExists,
                'existing_payment_status' => $existingPaymentStatus,
                'existing_payment_amount' => $existingPaymentAmount,
                'message' => $paymentExists ? 'Payment already exists for this month' : 'You can add meal payment'
            ]);
        }

        // Handle room rent payment
        if ($paymentType === 'room_rent') {
            $roomRent = $this->roomRentService->getMemberRoomRent($memberId, $currentMonth);
            $monthlyRent = $roomRent['monthly_rent'] ?? 0;
            $extraPaymentReduction = $roomRent['extra_payment_reduction'] ?? 0;
            $finalRent = $roomRent['final_rent'] ?? 0;
            
            // Get already paid room rent for current month
            $paidAmount = Payment::where('member_id', $memberId)
                ->where('payment_type', 'room_rent')
                ->where(function($query) use ($currentMonth, $currentMonthAbbr) {
                    $query->where('month', $currentMonth);
                    if ($currentMonthAbbr) {
                        $query->orWhere('month', $currentMonthAbbr);
                    }
                })
                ->where('status', 1)
                ->sum('payment_amount');
            
            $dueAmount = max(0, $finalRent - $paidAmount);
            
            return response()->json([
                'success' => true,
                'amount' => round($dueAmount, 2),
                'total_bill' => round($monthlyRent, 2),
                'extra_reduction' => round($extraPaymentReduction, 2),
                'final_rent' => round($finalRent, 2),
                'paid' => round($paidAmount, 2),
                'due' => round($dueAmount, 2),
                'payment_exists' => $paymentExists,
                'existing_payment_status' => $existingPaymentStatus,
                'existing_payment_amount' => $existingPaymentAmount,
                'message' => $paymentExists ? 'Payment already exists for this month' : ''
            ]);
        }

        // Handle room advance payment
        if ($paymentType === 'room_advance') {
            $roomRent = $this->roomRentService->getMemberRoomRent($memberId, $currentMonth);
            $advancePaid = $roomRent['advance_paid'] ?? 0;
            
            return response()->json([
                'success' => true,
                'amount' => 0,
                'total_bill' => $advancePaid,
                'paid' => $advancePaid,
                'due' => 0,
                'payment_exists' => $paymentExists,
                'existing_payment_status' => $existingPaymentStatus,
                'existing_payment_amount' => $existingPaymentAmount,
                'message' => 'Room advance is typically paid when assigning to a room'
            ]);
        }

        // Handle bill payments (water, internet, electricity, gas, bua_moyla)
        $billType = $paymentType;
        
        // Get the bill for current month and type (check both full and abbreviated forms)
        $bill = Bill::where('bill_type', $billType)
            ->where(function($query) use ($currentMonth, $currentMonthAbbr) {
                $query->where('month', $currentMonth);
                if ($currentMonthAbbr) {
                    $query->orWhere('month', $currentMonthAbbr);
                }
            })
            ->where('status', 1)
            ->first();

        if (!$bill) {
            return response()->json([
                'success' => false,
                'amount' => 0,
                'payment_exists' => $paymentExists,
                'existing_payment_status' => $existingPaymentStatus,
                'message' => 'No bill found for current month'
            ]);
        }

        // Get amount for this specific member (handles gas bill extra users)
        $amount = $bill->getPerPersonAmountForMember($memberId);

        // Get already paid amount for this bill type (check both month formats)
        $paidAmount = Payment::where('member_id', $memberId)
            ->where('payment_type', $billType)
            ->where(function($query) use ($currentMonth, $currentMonthAbbr) {
                $query->where('month', $currentMonth);
                if ($currentMonthAbbr) {
                    $query->orWhere('month', $currentMonthAbbr);
                }
            })
            ->where('status', 1)
            ->sum('payment_amount');

        $dueAmount = max(0, $amount - $paidAmount);

        return response()->json([
            'success' => true,
            'amount' => round($dueAmount, 2),
            'total_bill' => round($amount, 2),
            'paid' => round($paidAmount, 2),
            'due' => round($dueAmount, 2),
            'payment_exists' => $paymentExists,
            'existing_payment_status' => $existingPaymentStatus,
            'existing_payment_amount' => $existingPaymentAmount,
            'message' => $paymentExists ? 'Payment already exists for this month' : ''
        ]);
    }

}
