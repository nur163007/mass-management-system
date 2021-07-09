<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
class UserReportController extends Controller
{
    public function index(){
      $meals = DB::table('meals')->select('meals.*')->groupBy('month')->get();
      return view('userpanel.report.user_report',compact('meals'));
  }

  public function detailsReport(Request $request){
    	// dd("ok");
    	// return ($request);
     $id = session()->get('member_id');
     $start_month = date('Y-m-d',strtotime($request->from_date));
     $finish_month = date('Y-m-d',strtotime($request->to_date));

     $meals = DB::select("SELECT * from meals where status = '1' and date BETWEEN '$start_month' and '$finish_month'");
        // dd($meals);
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
        // dd($expanses);

    $total_amount =0;

    foreach($expanses as $ex){
      $total_amount += $ex->total_amount; 
  }
	        // dd($total_amount);
  $meal_rate = ($total_amount / $total_meal);
	        // dd($meal_rate);

  $data = DB::select("SELECT SUM(payments.payment_amount) as total_amount,m.id as mem_id FROM payments
    INNER JOIN members as m ON payments.member_id = m.id
    where payments.status = '1' and m.id = '$id' and payments.date BETWEEN '$start_month' and '$finish_month'");

        // dd($data);

  return view('userpanel.report.user_details',compact('data','total_amount','meal_rate','start_month','finish_month'));
}

public function downloadPdf($start_month,$finish_month){
// dd($finish_month);
     $id = session()->get('member_id');
    
    $meals = DB::select("SELECT * from meals where status = '1' and date BETWEEN '$start_month' and '$finish_month'");
        // dd($meals);
    $br = 0;
    $ln = 0;
    $dn = 0;

    foreach($meals as $m){
        $br += $m->breakfast;
        $ln += $m->lunch;
        $dn += $m->dinner;
    }

    $total_meal = $br +  $ln + $dn;
// dd($total_meal);
    $expanses =DB::select("SELECT * from expanses where status = '1' and date BETWEEN '$start_month' and '$finish_month';");
        // dd($expanses);

    $total_amount =0;

    foreach($expanses as $ex){
      $total_amount += $ex->total_amount; 
  }
            // dd($total_amount);
  $meal_rate = ($total_amount / $total_meal);
            // dd($meal_rate);

  $data = DB::select("SELECT SUM(payments.payment_amount) as total_amount,m.id as mem_id FROM payments
    INNER JOIN members as m ON payments.member_id = m.id
    where payments.status = '1' and m.id = '$id' and payments.date BETWEEN '$start_month' and '$finish_month'");

        // dd($data);

  $pdf = PDF::loadView('userpanel.report.userReportPdf',compact('data','total_amount','meal_rate','start_month','finish_month'));

  return $pdf->download('data.pdf','total_amount.pdf','meal_rate.pdf','start_month.pdf','finish_month.pdf');
}
}
