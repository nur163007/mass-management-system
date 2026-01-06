<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index(){
        // $meals = DB::select(" SELECT SUM(breakfast+lunch+dinner)as total_meal from meals");
            $meals = DB::select("SELECT * from meals");
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
            $meal_rate = $total_meal > 0 ? ($total_amount / $total_meal) : 0;
            // dd($meal_rate);
            // $date = date('M');
            // dd($date);
            // Only include food_advance payments
            $payment = DB::table('members')
            ->join('payments','members.id','payments.member_id')
            ->select('payments.payment_amount')
            ->where('payments.payment_type', 'food_advance')
            ->where('payments.status', '1')
            ->groupBy('members.id')
            ->get();
            //   dd($payment);

            // Only include food_advance payments for food report, exclude Super Admin (role_id = 1)
            $summary = DB::select("SELECT SUM(meals.breakfast + meals.lunch + meals.dinner) as total_meal, meals.month,m.id,m.full_name,SUM(CASE WHEN payments.payment_type = 'food_advance' AND payments.status = '1' THEN payments.payment_amount ELSE 0 END) as payment_amount
                FROM ((members m
                LEFT JOIN payments ON m.id = payments.member_id AND payments.payment_type = 'food_advance' AND payments.status = '1')
                INNER JOIN meals ON m.id = meals.members_id)
                WHERE m.role_id != 1
                GROUP BY m.id,meals.month");
        // dd($summary);
            return view('admin.summary.viewSummary',compact('total_meal','total_amount','meal_rate','payment','summary'));

    }

    public function memberDetails($member_id){
        // dd($member_id);
        $meals = DB::table('meals')->join('members','meals.members_id','members.id')->select('meals.*')->where('meals.members_id',$member_id)->get();
        //    dd($meals);

        $members = DB::table('meals')->join('members','meals.members_id','members.id')->select('members.*','meals.*')->where('meals.members_id',$member_id)->first();
        //    dd($members);

        $total = DB::select("SELECT SUM(breakfast+lunch+dinner)as total_meal from meals where members_id = '$member_id'");
        // dd($total);
      
        
        return view('admin.summary.memberDetails',compact('meals','members','total'));

        }
}
