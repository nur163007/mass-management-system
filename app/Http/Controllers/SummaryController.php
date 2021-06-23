<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index(){
        // $meals = DB::select(" SELECT SUM(breakfast+lunch+dinner)as total_meal from meals");
            $meals = DB::select(" SELECT * from meals");
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
            // $expanses = DB::select(" SELECT SUM(total_amount)as total from expanses");
            $expanses = DB::select(" SELECT * from expanses");

        //    dd($expanses);
            $total_amount =0;
            foreach($expanses as $ex){
            $total_amount += $ex->total_amount; 
            }
            // dd($total_amount);
            $meal_rate = ($total_amount / $total_meal);
            // dd($meal_rate);
            // $date = date('M');
            // dd($date);
            $payment = DB::table('members')
            ->join('payments','members.id','payments.member_id')
            ->select('payments.payment_amount')
            ->groupBy('members.id')
            ->get();
            //   dd($payment);

            $summary = DB::select("SELECT SUM(meals.breakfast + meals.lunch + meals.dinner) as total_meal, meals.month,m.id,m.full_name,payments.payment_amount
                FROM ((members m
                INNER JOIN payments ON m.id = payments.member_id)
                INNER JOIN meals ON m.id = meals.members_id) GROUP BY m.id,meals.month");
        // dd($summary);
            return view('admin.summary.viewSummary',compact('total_meal','total_amount','meal_rate','payment','summary'));


    }
}
