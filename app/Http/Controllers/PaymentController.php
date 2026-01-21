<?php

namespace App\Http\Controllers; 
use App\Models\Member;
use App\Models\Payment;
use App\Models\Bill;
use App\Models\BillTypeResponsibility;
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
        // Check authorization
        $loggedInMemberId = session()->get('member_id');
        if (!$loggedInMemberId) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $loggedInMember = Member::find($loggedInMemberId);
        if (!$loggedInMember) {
            return redirect('/')->with('error', 'Member not found.');
        }
        
        $isSuperAdmin = $loggedInMember->role_id == 1;
        $isManager = $loggedInMember->role_id == 2;
        
        $myResponsibilities = BillTypeResponsibility::where('member_id', $loggedInMemberId)
            ->pluck('bill_type')
            ->toArray();
        
        // Authorization: Super Admin, Manager, or users with responsibilities can access
        if (!$isSuperAdmin && !$isManager && empty($myResponsibilities)) {
            return redirect('/')->with('error', 'You are not authorized to add payments.');
        }
        
        // Get Manager (2) and User (3) members only, exclude Super Admin (1)
        $members = Member::whereIn('role_id', [2, 3])->where('status', 1)->get();
        
        return view('admin.payment.addPayment',compact('members', 'myResponsibilities', 'isSuperAdmin', 'isManager'));
    }

    public function storePayment(Request $request){
        // Check authorization
        $loggedInMemberId = session()->get('member_id');
        if (!$loggedInMemberId) {
            return response()->json(['error' => 'Please login first.'], 401);
        }
        
        $loggedInMember = Member::find($loggedInMemberId);
        if (!$loggedInMember) {
            return response()->json(['error' => 'Member not found.'], 404);
        }
        
        $isSuperAdmin = $loggedInMember->role_id == 1;
        $isManager = $loggedInMember->role_id == 2;
        
        $myResponsibilities = BillTypeResponsibility::where('member_id', $loggedInMemberId)
            ->pluck('bill_type')
            ->toArray();
        
        // Authorization: Super Admin, Manager, or users with responsibilities can add payment
        if (!$isSuperAdmin && !$isManager && empty($myResponsibilities)) {
            return response()->json(['error' => 'You are not authorized to add payments.'], 403);
        }
        
        // For regular users, check if they're adding payment for their responsible bill type
        if (!$isSuperAdmin && !$isManager && !in_array($request->payment_type, $myResponsibilities)) {
            return response()->json(['error' => 'You are not authorized to add payment for this bill type.'], 403);
        }
        
        $this->validate($request,[
            'member_id' => 'required',
            'payment_type' => 'required|in:food_advance,room_rent,room_advance,water,internet,electricity,gas,bua_moyla',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required',
            'month' => 'required',
        ]);

        // Check if member is Super Admin - prevent payment for Super Admin
        $member = Member::find($request->member_id);
        if (!$member) {
            return response()->json(['error' => 'Member not found.'], 404);
        }
        if ($member->role_id == 1) {
            return response()->json(['error' => 'Cannot add payment for Super Admin.'], 403);
        }

        $date_convert = date('Y-m-d',strtotime($request->date));
        $month = $request->month;

        // Determine payment status:
        // 1. Manager adds food_advance payment = auto-approved
        // 2. Manager adds payment for their responsible bill type = auto-approved
        // 3. Responsible person adds payment for their bill type = auto-approved
        // 4. Others = pending (needs approval)
        $paymentStatus = "0"; // Default: pending
        
        if ($isManager && $request->payment_type === 'food_advance') {
            // Manager adds food advance payment - auto approve
            $paymentStatus = "1";
        } elseif ($isManager && in_array($request->payment_type, $myResponsibilities)) {
            // Manager adds payment for their responsible bill type - auto approve
            $paymentStatus = "1";
        } elseif (!$isSuperAdmin && !$isManager && in_array($request->payment_type, $myResponsibilities)) {
            // Responsible person (regular user) adds payment for their bill type - auto approve
            $paymentStatus = "1";
        }

        $payments = new Payment;

        $payments->member_id = $request->member_id;
        $payments->payment_amount = $request->amount;
        $payments->payment_type = $request->payment_type;
        $payments->date = $date_convert;
        $payments->month = $month;
        $payments->status = $paymentStatus;
        $payments->notes = $request->notes ?? null;


        if ($payments->save()) {
            $message = 'success';
            if ($paymentStatus == "1") {
                if ($isManager && $request->payment_type === 'food_advance') {
                    $message = 'Payment automatically approved (Manager food advance).';
                } elseif ($isManager && in_array($request->payment_type, $myResponsibilities)) {
                    $message = 'Payment automatically approved (Manager responsible for this bill type).';
                } else {
                    $message = 'Payment automatically approved (you are responsible for this bill type).';
                }
            }
            return response()->json($message);

        }
        else{
            return response()->json("error");
        } 
    }

    public function viewPayment(Request $request){
        $query = DB::table('payments')
        ->join('members','payments.member_id','members.id')
        ->select('payments.*','members.full_name')
        ->where('members.role_id', '!=', 1); // Exclude Super Admin payments
        
        // Get logged-in member's responsibilities
        $loggedInMemberId = session()->get('member_id');
        $loggedInMember = Member::find($loggedInMemberId);
        $isSuperAdmin = $loggedInMember && $loggedInMember->role_id == 1;
        $isManager = $loggedInMember && $loggedInMember->role_id == 2;
        
        $myResponsibilities = BillTypeResponsibility::where('member_id', $loggedInMemberId)
            ->pluck('bill_type')
            ->toArray();
        
        // Manager can see all payments, but regular users see only their responsible bill types
        if (!$isSuperAdmin && !$isManager && !empty($myResponsibilities)) {
            $query->whereIn('payments.payment_type', $myResponsibilities);
        }
        
        // Filter by member if provided
        if($request->has('member_id') && $request->member_id != ''){
            $query->where('payments.member_id', $request->member_id);
        }
        
        // Filter by payment type if provided
        if($request->has('payment_type') && $request->payment_type != ''){
            $query->where('payments.payment_type', $request->payment_type);
        }
        
        // Get all payments for grouping
        $allPayments = $query->orderBy('payments.created_at', 'DESC')
            ->orderBy('payments.id', 'DESC')
            ->get();
        
        // Group payments by member_id and month, sum amounts
        $groupedPayments = [];
        foreach ($allPayments as $payment) {
            $key = $payment->member_id . '_' . $payment->month;
            if (!isset($groupedPayments[$key])) {
                $groupedPayments[$key] = [
                    'member_id' => $payment->member_id,
                    'full_name' => $payment->full_name,
                    'month' => $payment->month,
                    'total_amount' => 0,
                ];
            }
            $groupedPayments[$key]['total_amount'] += $payment->payment_amount;
        }
        
        // Convert to array and sort by month (descending), then by member name
        $payments = array_values($groupedPayments);
        usort($payments, function($a, $b) {
            $monthOrder = [
                'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
                'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
                'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12,
                'Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4,
                'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Aug' => 8,
                'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12
            ];
            $aOrder = $monthOrder[$a['month']] ?? 99;
            $bOrder = $monthOrder[$b['month']] ?? 99;
            
            // First sort by month (descending)
            if ($bOrder != $aOrder) {
                return $bOrder - $aOrder;
            }
            // Then sort by member name (ascending)
            return strcmp($a['full_name'], $b['full_name']);
        });
        
        // Get members for dropdown (exclude Super Admin)
        $members = Member::whereIn('role_id', [2, 3])->orderBy('full_name')->get();
        
        $paymentTypes = [
            'food_advance' => 'Food Advance',
            'room_rent' => 'Room Rent',
            'room_advance' => 'Room Advance',
            'water' => 'Water Bill',
            'internet' => 'Internet Bill',
            'electricity' => 'Electricity Bill',
            'gas' => 'Gas Bill',
            'bua_moyla' => 'Bua & Moyla Bill',
        ];
        
        // Filter payment types dropdown: Manager sees all, regular users see only responsible types
        if (!$isSuperAdmin && !$isManager && !empty($myResponsibilities)) {
            $paymentTypes = array_filter($paymentTypes, function($key) use ($myResponsibilities) {
                return in_array($key, $myResponsibilities);
            }, ARRAY_FILTER_USE_KEY);
        }
        
        $selectedMemberId = $request->member_id ?? '';
        $selectedPaymentType = $request->payment_type ?? '';
        
        return view('admin.payment.viewPayment',compact('payments', 'members', 'paymentTypes', 'selectedMemberId', 'selectedPaymentType', 'myResponsibilities', 'isSuperAdmin', 'isManager'));
    }

    public function paymentStatus($id, $status){
        $active = Payment::findOrFail($id);
        $loggedInMemberId = session()->get('member_id');
        
        // Check if logged-in user is responsible for this payment type
        $responsibility = BillTypeResponsibility::where('bill_type', $active->payment_type)
            ->where('member_id', $loggedInMemberId)
            ->first();
        
        // Get logged-in member details
        $loggedInMember = Member::find($loggedInMemberId);
        $isSuperAdmin = $loggedInMember && $loggedInMember->role_id == 1;
        $isManager = $loggedInMember && $loggedInMember->role_id == 2;
        
        // Authorization check:
        // 1. Super Admin can approve all
        // 2. Manager can approve food_advance and their responsible bill types
        // 3. Regular users can approve only their responsible bill types
        $canApprove = false;
        
        if ($isSuperAdmin) {
            $canApprove = true;
        } elseif ($isManager) {
            // Manager can approve food_advance or their responsible bill types
            if ($active->payment_type === 'food_advance' || $responsibility) {
                $canApprove = true;
            }
        } else {
            // Regular users can approve only their responsible bill types
            if ($responsibility) {
                $canApprove = true;
            }
        }
        
        if (!$canApprove) {
            return response()->json([
                'message' => 'You are not authorized to approve this payment. ' . 
                    ($isManager ? 'Manager can approve Food Advance and assigned bill types only.' : 
                    'Only the responsible member for ' . ucfirst(str_replace('_', ' ', $active->payment_type)) . ' can approve it.')
            ], 403);
        }
        
        $active->status = $status; 

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
        // Check authorization
        $loggedInMemberId = session()->get('member_id');
        if (!$loggedInMemberId) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $loggedInMember = Member::find($loggedInMemberId);
        if (!$loggedInMember) {
            return redirect('/')->with('error', 'Member not found.');
        }
        
        $isSuperAdmin = $loggedInMember->role_id == 1;
        $isManager = $loggedInMember->role_id == 2;
        
        $myResponsibilities = BillTypeResponsibility::where('member_id', $loggedInMemberId)
            ->pluck('bill_type')
            ->toArray();
        
        // Get payment details
        $payments=DB::table('payments')
        ->join('members','payments.member_id','members.id')
        ->select('members.full_name','payments.*')
        ->where('payments.id',$id)
        ->first();
        
        if(!$payments){
            return redirect()->route('admin.view.payment')->with('error', 'Payment not found.');
        }
        
        // Authorization: Super Admin, Manager, or responsible member for this payment type
        $canEdit = false;
        if ($isSuperAdmin || $isManager) {
            $canEdit = true;
        } elseif (in_array($payments->payment_type, $myResponsibilities)) {
            $canEdit = true;
        }
        
        if (!$canEdit) {
            return redirect()->route('admin.view.payment')->with('error', 'You are not authorized to edit this payment.');
        }

        // Exclude Super Admin (role_id = 1), only include Manager (2) and User (3)
        $members = Member::whereIn('role_id', [2, 3])->where('status', 1)->get();
        return view('admin.payment.editPayment',compact('payments','members'));
    }


    public function updatePayment(Request $request){
        // Check authorization
        $loggedInMemberId = session()->get('member_id');
        if (!$loggedInMemberId) {
            return response()->json(['error' => 'Please login first.'], 401);
        }
        
        $loggedInMember = Member::find($loggedInMemberId);
        if (!$loggedInMember) {
            return response()->json(['error' => 'Member not found.'], 404);
        }
        
        $isSuperAdmin = $loggedInMember->role_id == 1;
        $isManager = $loggedInMember->role_id == 2;
        
        $myResponsibilities = BillTypeResponsibility::where('member_id', $loggedInMemberId)
            ->pluck('bill_type')
            ->toArray();
        
        $this->validate($request,[
            'member_id' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required',
        ]);

        $id = $request->paymentID;
        $payments = Payment::findOrFail($id);
        
        if (!$payments) {
            return response()->json(['error' => 'Payment not found.'], 404);
        }
        
        // Authorization: Super Admin, Manager, or responsible member for this payment type
        $canEdit = false;
        if ($isSuperAdmin || $isManager) {
            $canEdit = true;
        } elseif (in_array($payments->payment_type, $myResponsibilities)) {
            $canEdit = true;
        }
        
        if (!$canEdit) {
            return response()->json(['error' => 'You are not authorized to edit this payment.'], 403);
        }
        
        // Check if member is Super Admin - prevent payment for Super Admin
        $member = Member::find($request->member_id);
        if (!$member) {
            return response()->json(['error' => 'Member not found.'], 404);
        }
        if ($member->role_id == 1) {
            return response()->json(['error' => 'Cannot update payment for Super Admin.'], 403);
        }
        
        $date_convert = date('Y-m-d',strtotime($request->date));
        $month = date('F',strtotime($request->date));

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

    public function viewPaymentDetails($id){
        $payment = DB::table('payments')
            ->join('members', 'payments.member_id', '=', 'members.id')
            ->select('payments.*', 'members.full_name', 'members.phone_no', 'members.email')
            ->where('payments.id', $id)
            ->first();
        
        if(!$payment){
            return redirect()->route('admin.view.payment')->with('error', 'Payment not found.');
        }

        return view('admin.payment.viewDetails', compact('payment'));
    }

    public function downloadPdf(){
        // Check authorization
        $loggedInMemberId = session()->get('member_id');
        if (!$loggedInMemberId) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $loggedInMember = Member::find($loggedInMemberId);
        if (!$loggedInMember) {
            return redirect('/')->with('error', 'Member not found.');
        }
        
        $isSuperAdmin = $loggedInMember->role_id == 1;
        $isManager = $loggedInMember->role_id == 2;
        
        $myResponsibilities = BillTypeResponsibility::where('member_id', $loggedInMemberId)
            ->pluck('bill_type')
            ->toArray();
        
        // Authorization: Super Admin, Manager, or users with responsibilities can download
        if (!$isSuperAdmin && !$isManager && empty($myResponsibilities)) {
            return redirect()->route('admin.view.payment')->with('error', 'You are not authorized to download payments.');
        }
        
        $query = DB::table('payments')
        ->join('members','payments.member_id','members.id')
        ->select('payments.*','members.full_name')
        ->where('members.role_id', '!=', 1); // Exclude Super Admin payments
        
        // Manager can download all, regular users can download only their responsible bill types
        if (!$isSuperAdmin && !$isManager && !empty($myResponsibilities)) {
            $query->whereIn('payments.payment_type', $myResponsibilities);
        }
        
        $payments = $query->get();

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
        
        // Check if member is Super Admin - prevent bill calculation for Super Admin
        $member = Member::find($memberId);
        if (!$member) {
            return response()->json([
                'success' => false,
                'amount' => 0,
                'message' => 'Member not found.'
            ], 404);
        }
        if ($member->role_id == 1) {
            return response()->json([
                'success' => false,
                'amount' => 0,
                'message' => 'Cannot calculate bill for Super Admin.'
            ], 403);
        }

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
        
        // Month format conversion map
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        
        // Convert month to full form
        $monthFull = $monthMap[$month] ?? $month;
        
        // Get abbreviated form if month is in full form
        $reverseMap = array_flip($monthMap);
        $monthAbbr = $reverseMap[$monthFull] ?? null;
        
        // Get the bill for this month and type (check both full and abbreviated forms)
        $bill = Bill::where('bill_type', $billType)
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
            ->first();

        if (!$bill) {
            return response()->json([
                'success' => false,
                'amount' => 0,
                'message' => 'No bill found for this month'
            ]);
        }

        // Get amount for this specific member (handles gas bill extra users)
        // This calculates: total_amount / applicable_members (or special logic for gas)
        $amount = $bill->getPerPersonAmountForMember($memberId);

        // Get already paid amount for this bill type (check both month formats)
        $paidAmount = Payment::where('member_id', $memberId)
            ->where('payment_type', $billType)
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

    /**
     * Get payment details for a specific member and month
     */
    public function getPaymentDetails($memberId, $month)
    {
        // Check authorization
        $loggedInMemberId = session()->get('member_id');
        if (!$loggedInMemberId) {
            return response()->json(['success' => false, 'message' => 'Please login first.'], 401);
        }
        
        $loggedInMember = Member::find($loggedInMemberId);
        if (!$loggedInMember) {
            return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
        }
        
        $isSuperAdmin = $loggedInMember->role_id == 1;
        $isManager = $loggedInMember->role_id == 2;
        
        $myResponsibilities = BillTypeResponsibility::where('member_id', $loggedInMemberId)
            ->pluck('bill_type')
            ->toArray();
        
        // Month format conversion map
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        $reverseMap = array_flip($monthMap);
        $monthAbbr = $reverseMap[$month] ?? null;
        
        // Get all individual payments for this member and month (exclude Super Admin)
        $payments = Payment::where('member_id', $memberId)
            ->whereHas('member', function($query) {
                $query->where('role_id', '!=', 1); // Exclude Super Admin
            })
            ->where(function($query) use ($month, $monthAbbr) {
                $query->where('month', $month);
                if ($monthAbbr) {
                    $query->orWhere('month', $monthAbbr);
                }
            })
            ->orderBy('date', 'DESC')
            ->orderBy('payment_type', 'ASC')
            ->get();
        
        // Filter by responsibilities if not super admin or manager
        if (!$isSuperAdmin && !$isManager && !empty($myResponsibilities)) {
            $payments = $payments->filter(function($payment) use ($myResponsibilities) {
                return in_array($payment->payment_type, $myResponsibilities);
            });
        }
        
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
            
            // Check authorization for approve and edit
            $canApprove = false;
            $canEdit = false;
            
            // Check if user can approve this payment
            if ($isSuperAdmin) {
                $canApprove = true;
                $canEdit = true;
            } elseif ($isManager) {
                // Manager can approve food_advance or their responsible bill types
                $responsibility = BillTypeResponsibility::where('bill_type', $payment->payment_type)
                    ->where('member_id', $loggedInMemberId)
                    ->first();
                if ($payment->payment_type === 'food_advance' || $responsibility) {
                    $canApprove = true;
                }
                // Manager can edit food_advance or their responsible bill types
                if ($payment->payment_type === 'food_advance' || $responsibility) {
                    $canEdit = true;
                }
            } else {
                // Regular users can approve/edit only their responsible bill types
                $responsibility = BillTypeResponsibility::where('bill_type', $payment->payment_type)
                    ->where('member_id', $loggedInMemberId)
                    ->first();
                if ($responsibility) {
                    $canApprove = true;
                    $canEdit = true;
                }
            }
            
            // Can't edit if already approved/paid
            if ($payment->status == 1) {
                $canEdit = false;
            }
            
            $formattedPayments[] = [
                'id' => $payment->id,
                'type' => $typeName,
                'payment_type' => $payment->payment_type,
                'amount' => $payment->payment_amount,
                'date' => date('d M Y', strtotime($payment->date)),
                'status' => $payment->status,
                'status_text' => $payment->status == 1 ? 'Paid' : 'Pending',
                'notes' => $payment->notes ?? '',
                'can_approve' => $canApprove,
                'can_edit' => $canEdit
            ];
            $grandTotal += $payment->payment_amount;
        }
        
        return response()->json([
            'success' => true,
            'member_id' => $memberId,
            'month' => $month,
            'payments' => $formattedPayments,
            'total' => $grandTotal
        ]);
    }
}
