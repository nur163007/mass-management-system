<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class ReportController extends Controller
{
    public function report(){
        $meals = DB::table('meals')
            ->select('meals.*')
            ->groupBy('month')
            ->orderByRaw("FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->get();
        return view('admin.report.index',compact('meals'));
    }

    public function view(Request $request){
        // return ($request);

        $start_month = date('Y-m-d',strtotime($request->from_date));
        $finish_month = date('Y-m-d',strtotime($request->to_date));

        // Exclude Super Admin (role_id = 1) from meals
        $meals = DB::select("SELECT meals.* from meals 
            INNER JOIN members ON meals.members_id = members.id 
            where meals.status = '1' and members.role_id != 1 and meals.date BETWEEN '$start_month' and '$finish_month'");
        // return $meals;
        $br = 0;
        $ln = 0;
        $dn = 0;

        foreach($meals as $m){
            $br += $m->breakfast;
            $ln += $m->lunch;
            $dn += $m->dinner;
        }

        $total_meal = $br +  $ln + $dn;

        // Exclude Super Admin (role_id = 1) from expanses
        $expanses = DB::select("SELECT expanses.* from expanses 
            INNER JOIN members ON expanses.member_id = members.id 
            where expanses.status = '1' and members.role_id != 1 and expanses.date BETWEEN '$start_month' and '$finish_month'");
        // return $expanses;


        $total_amount =0;

        foreach($expanses as $ex){
           $total_amount += $ex->total_amount; 
       }
        // dd($total_amount);
       $meal_rate = $total_meal > 0 ? ($total_amount / $total_meal) : 0;
        // return $meal_rate;

       // Only include food_advance payments for food report, exclude Super Admin (role_id = 1)
       $payments = DB::select("SELECT payments.* from payments 
           INNER JOIN members ON payments.member_id = members.id 
           where payments.status = '1' and payments.payment_type = 'food_advance' and members.role_id != 1 and payments.date BETWEEN '$start_month' and '$finish_month'");
        // return $payments;
       $fund =0;
       foreach($payments as $pay){
        $fund += $pay->payment_amount; 
    }
        // return $fund;
    $total_fund = $fund - $total_amount;
        // return $total_fund;

    // Get all members except Super Admin (role_id = 1)
    $allMembers = DB::select("SELECT m.id as member_id, m.full_name as member_name FROM members as m WHERE m.role_id != 1 ORDER BY m.full_name");
    
    // Get payment data for members who have food_advance payments
    $paymentData = DB::select("SELECT SUM(payments.payment_amount) as total_amount, payments.member_id FROM payments
        INNER JOIN members as m ON payments.member_id = m.id
        where payments.status = '1' and payments.payment_type = 'food_advance' and m.role_id != 1 and payments.date BETWEEN '$start_month' and '$finish_month'
        GROUP BY payments.member_id");
    
    // Create a map of member_id => total_amount for quick lookup
    $paymentMap = [];
    foreach($paymentData as $pay){
        $paymentMap[$pay->member_id] = $pay->total_amount;
    }
    
    // Combine all members with their payment data
    $data = [];
    foreach($allMembers as $member){
        $data[] = (object)[
            'member_id' => $member->member_id,
            'member_name' => $member->member_name,
            'total_amount' => $paymentMap[$member->member_id] ?? 0
        ];
    }

        // dd($data);

    return view('admin.report.report_details',compact('data','total_meal','fund','total_amount','meal_rate','total_fund','start_month','finish_month'));
}


public function downloadPdf($start_month,$finish_month){

// dd($start_month);
    // $start_month = date('Y-m-d',strtotime($request->from_date));
    // $finish_month = date('Y-m-d',strtotime($request->to_date));

    // Exclude Super Admin (role_id = 1) from meals
    $meals = DB::select("SELECT meals.* from meals 
        INNER JOIN members ON meals.members_id = members.id 
        where meals.status = '1' and members.role_id != 1 and meals.date BETWEEN '$start_month' and '$finish_month'");
        // return $meals;
    $br = 0;
    $ln = 0;
    $dn = 0;

    foreach($meals as $m){
        $br += $m->breakfast;
        $ln += $m->lunch;
        $dn += $m->dinner;
    }

    $total_meal = $br +  $ln + $dn;

    // Exclude Super Admin (role_id = 1) from expanses
    $expanses = DB::select("SELECT expanses.* from expanses 
        INNER JOIN members ON expanses.member_id = members.id 
        where expanses.status = '1' and members.role_id != 1 and expanses.date BETWEEN '$start_month' and '$finish_month'");
        // return $expanses;


    $total_amount =0;

    foreach($expanses as $ex){
       $total_amount += $ex->total_amount; 
}
        // dd($total_amount);
   $meal_rate = $total_meal > 0 ? ($total_amount / $total_meal) : 0;
        // return $meal_rate;

   // Only include food_advance payments for food report, exclude Super Admin (role_id = 1)
   $payments = DB::select("SELECT payments.* from payments 
       INNER JOIN members ON payments.member_id = members.id 
       where payments.status = '1' and payments.payment_type = 'food_advance' and members.role_id != 1 and payments.date BETWEEN '$start_month' and '$finish_month'");
        // return $payments;
   $fund =0;
   foreach($payments as $pay){
    $fund += $pay->payment_amount; 
}
        // return $fund;
$total_fund = $fund - $total_amount;
        // return $total_fund;

// Get all members except Super Admin (role_id = 1)
$allMembers = DB::select("SELECT m.id as member_id, m.full_name as member_name FROM members as m WHERE m.role_id != 1 ORDER BY m.full_name");

// Get payment data for members who have food_advance payments
$paymentData = DB::select("SELECT SUM(payments.payment_amount) as total_amount, payments.member_id FROM payments
    INNER JOIN members as m ON payments.member_id = m.id
    where payments.status = '1' and payments.payment_type = 'food_advance' and m.role_id != 1 and payments.date BETWEEN '$start_month' and '$finish_month'
    GROUP BY payments.member_id");

// Create a map of member_id => total_amount for quick lookup
$paymentMap = [];
foreach($paymentData as $pay){
    $paymentMap[$pay->member_id] = $pay->total_amount;
}

// Combine all members with their payment data
$data = [];
foreach($allMembers as $member){
    $data[] = (object)[
        'member_id' => $member->member_id,
        'member_name' => $member->member_name,
        'total_amount' => $paymentMap[$member->member_id] ?? 0
    ];
}

$pdf = PDF::loadView('admin.report.reportPdf',compact('data','total_meal','fund','total_amount','meal_rate','total_fund','start_month','finish_month'));

return $pdf->download('report.pdf');
}

}
