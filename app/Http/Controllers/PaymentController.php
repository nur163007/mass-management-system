<?php

namespace App\Http\Controllers; 
use App\Models\Member;
use App\Models\Payment;
use App\Models\Bill;
use App\Services\BillService;
use App\Services\RoomRentService;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $billService;
    protected $roomRentService;

    public function __construct(BillService $billService, RoomRentService $roomRentService)
    {
        $this->billService = $billService;
        $this->roomRentService = $roomRentService;
    }

    public function addPayment(){
        // Get Manager (2) and User (3) members only, exclude Super Admin (1)
        $members = Member::whereIn('role_id', [2, 3])->where('status', 1)->get();
        return view('admin.payment.addPayment',compact('members'));
    }

    public function storePayment(Request $request){
        // dd($request);
        $this->validate($request,[
            'member_id' => 'required',
            'payment_type' => 'required|in:food_advance,room_rent,room_advance,water,internet,electricity,gas,bua_moyla',
            'amount' => 'required',
            'date' => 'required',
            'month' => 'required',
        ]);

        $date_convert = date('Y-m-d',strtotime($request->date));
        $month = $request->month;

        $payments = new Payment;

        $payments->member_id = $request->member_id;
        $payments->payment_amount = $request->amount;
        $payments->payment_type = $request->payment_type;
        $payments->date = $date_convert;
        $payments->month = $month;
        $payments->status = "1";
        $payments->notes = $request->notes ?? null;


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

    public function paymentStatus($id, $status){
        // dd($id);
        $active = Payment::findOrFail($id);
        $active->status= $status; 

        if($active->save()){
            return response()->json(['message'=> 'Success']);
        }
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
        $month = date('M',strtotime($request->date));

        $payments = Payment::findOrFail($id);

        $payments->member_id = $request->member_id;
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

    public function downloadPdf(){
        $payments = DB::table('payments')
        ->join('members','payments.member_id','members.id')
        ->select('payments.*','members.full_name')
        ->get();

        $pdf = PDF::loadView('admin.payment.paymentPdf',compact('payments'));
        return $pdf->download('payments.pdf');
    }

    /**
     * Get bill amount for a specific member and bill type
     */
    public function getBillAmount(Request $request)
    {
        $this->validate($request, [
            'member_id' => 'required|exists:members,id',
            'bill_type' => 'required|in:water,internet,electricity,gas,bua_moyla,room_rent,room_advance',
            'month' => 'required',
        ]);

        $memberId = $request->member_id;
        $paymentType = $request->bill_type;
        $month = $request->month;

        // Handle room rent payment
        if ($paymentType === 'room_rent') {
            $roomRent = $this->roomRentService->getMemberRoomRent($memberId, $month);
            $monthlyRent = $roomRent['monthly_rent'] ?? 0;
            $extraPaymentReduction = $roomRent['extra_payment_reduction'] ?? 0;
            $finalRent = $roomRent['final_rent'] ?? 0;
            
            // Get already paid room rent for this month
            $paidAmount = Payment::where('member_id', $memberId)
                ->where('payment_type', 'room_rent')
                ->where('month', $month)
                ->where('status', 1)
                ->sum('payment_amount');
            
            $dueAmount = max(0, $finalRent - $paidAmount);
            
            return response()->json([
                'success' => true,
                'amount' => $dueAmount,
                'total_bill' => $monthlyRent,
                'extra_reduction' => $extraPaymentReduction,
                'final_rent' => $finalRent,
                'paid' => $paidAmount,
                'due' => $dueAmount
            ]);
        }

        // Handle room advance payment
        if ($paymentType === 'room_advance') {
            $roomRent = $this->roomRentService->getMemberRoomRent($memberId, $month);
            $advancePaid = $roomRent['advance_paid'] ?? 0;
            
            // Room advance is typically a one-time payment when assigning to room
            $dueAmount = 0;
            
            return response()->json([
                'success' => true,
                'amount' => $dueAmount,
                'total_bill' => $advancePaid,
                'paid' => $advancePaid,
                'due' => $dueAmount,
                'message' => 'Room advance is typically paid when assigning to a room'
            ]);
        }

        // Handle bill payments (water, internet, electricity, gas, bua_moyla)
        $billType = $paymentType;
        
        // Get the bill for this month and type
        $bill = Bill::where('bill_type', $billType)
            ->where('month', $month)
            ->where('status', 1)
            ->first();

        if (!$bill) {
            return response()->json([
                'success' => false,
                'amount' => 0,
                'message' => 'No bill found for this month'
            ]);
        }

        // Get amount for this specific member (handles gas bill extra users)
        $amount = $bill->getPerPersonAmountForMember($memberId);

        // Get already paid amount for this bill type
        $paidAmount = Payment::where('member_id', $memberId)
            ->where('payment_type', $billType)
            ->where('month', $month)
            ->where('status', 1)
            ->sum('payment_amount');

        $dueAmount = max(0, $amount - $paidAmount);

        return response()->json([
            'success' => true,
            'amount' => round($dueAmount, 2),
            'total_bill' => round($amount, 2),
            'paid' => round($paidAmount, 2),
            'due' => round($dueAmount, 2)
        ]);
    }
}
