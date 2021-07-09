<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;


class ReportController extends Controller
{
    public function report(){
        $meals = DB::table('meals')->select('meals.*')->groupBy('month')->get();
        return view('admin.report.index',compact('meals'));
    }

    public function view(Request $request){
        // return ($request);

        $start_month = date('Y-m-d',strtotime($request->from_date));
        $finish_month = date('Y-m-d',strtotime($request->to_date));

        $meals = DB::select("SELECT * from meals where status = '1' and date BETWEEN '$start_month' and '$finish_month';");
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

        $expanses =DB::select("SELECT * from expanses where status = '1' and date BETWEEN '$start_month' and '$finish_month';");
        // return $expanses;


        $total_amount =0;

        foreach($expanses as $ex){
           $total_amount += $ex->total_amount; 
       }
        // dd($total_amount);
       $meal_rate = ($total_amount / $total_meal);
        // return $meal_rate;

       $payments = DB::select("SELECT * from payments where status = '1' and date BETWEEN '$start_month' and '$finish_month'");
        // return $payments;
       $fund =0;
       foreach($payments as $pay){
        $fund += $pay->payment_amount; 
    }
        // return $fund;
    $total_fund = $fund - $total_amount;
        // return $total_fund;

    $data = DB::select("SELECT SUM(payments.payment_amount) as total_amount,m.full_name as member_name, m.id as member_id FROM payments
        INNER JOIN members as m ON payments.member_id = m.id
        where payments.status = '1' and payments.date BETWEEN '$start_month' and '$finish_month'
        GROUP BY  m.id");

        // dd($data);

    return view('admin.report.report_details',compact('data','total_meal','fund','total_amount','meal_rate','total_fund','start_month','finish_month'));
}


public function downloadPdf($start_month,$finish_month){

// dd($start_month);
    // $start_month = date('Y-m-d',strtotime($request->from_date));
    // $finish_month = date('Y-m-d',strtotime($request->to_date));

    $meals = DB::select("SELECT * from meals where status = '1' and date BETWEEN '$start_month' and '$finish_month';");
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

    $expanses =DB::select("SELECT * from expanses where status = '1' and date BETWEEN '$start_month' and '$finish_month';");
        // return $expanses;


    $total_amount =0;

    foreach($expanses as $ex){
       $total_amount += $ex->total_amount; 
   }
        // dd($total_amount);
   $meal_rate = ($total_amount / $total_meal);
        // return $meal_rate;

   $payments = DB::select("SELECT * from payments where status = '1' and date BETWEEN '$start_month' and '$finish_month'");
        // return $payments;
   $fund =0;
   foreach($payments as $pay){
    $fund += $pay->payment_amount; 
}
        // return $fund;
$total_fund = $fund - $total_amount;
        // return $total_fund;

$data = DB::select("SELECT SUM(payments.payment_amount) as total_amount,m.full_name as member_name, m.id as member_id FROM payments
    INNER JOIN members as m ON payments.member_id = m.id
    where payments.status = '1' and payments.date BETWEEN '$start_month' and '$finish_month'
    GROUP BY  m.id");

$pdf = PDF::loadView('admin.report.reportPdf',compact('data','total_meal','fund','total_amount','meal_rate','total_fund','start_month','finish_month'));

return $pdf->download('data.pdf','total_meal.pdf','fund.pdf','total_amount.pdf','meal_rate.pdf','total_fund.pdf','start_month.pdf','finish_month.pdf');
}

}
