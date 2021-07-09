<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meal;
use DB;

class UserMealController extends Controller
{
    public function index(){
	    	$id = session()->get('member_id');
	    	$meals = DB::select("SELECT m.id,SUM(meals.breakfast + meals.lunch + meals.dinner) as total_meal, meals.month,meals.status
	        FROM members m
	        INNER JOIN meals ON m.id = meals.members_id where meals.status ='1' and m.id = '$id' GROUP BY meals.month");
        // dd($meals);
        return view('userpanel.meal.user_meal',compact('meals'));
    }

    public function addMeal(){
    	return view('userpanel.meal.add_meal');
    }

    public function storeMeal(Request $request){
    	// dd('ok');
    	 $this->validate($request,[
            'date' => 'required',
            'breakfast' => 'required',
            'lunch' => 'required',
            'dinner' => 'required',

        ]);
        
        $month = date('M',strtotime($request->date));
        $convert_date = date('Y-m-d',strtotime($request->date));
        $meals = new Meal();

        $meals->members_id = session()->get('member_id');
        $meals->date = $convert_date;
        $meals->month = $month;
        $meals->breakfast = $request->breakfast;
        $meals->lunch = $request->lunch;
        $meals->dinner = $request->dinner;
        $meals->status = "0";

         // dd($meals);
        if ($meals->save()) {
            return response()->json($meals);
         }
         else{
            return response()->json("error");
         }  
    }

    public function pendingMeal(){

    	$id = session()->get('member_id');
	    $pending = DB::select("SELECT * FROM meals where status ='0' and members_id = '$id'");
    	return view('userpanel.meal.pending_meal',compact('pending'));
    }

    public function detailsMeal($id,$month){
    	// dd($id);
    	 $meals = DB::table('meals')->join('members','meals.members_id','members.id')->select('meals.*')->where('members.id',$id)->where('meals.month',$month)->get();
    	 // dd($meals);
    	return view('userpanel.meal.details_meal',compact('meals'));
    }

    public function editMeal($id){

	    $meals = DB::table('meals')->join('members','meals.members_id','members.id')->select('meals.*')->where('meals.id',$id)->first();
// dd($meals);
    	return view('userpanel.meal.edit_meal',compact('meals'));
    }

    public function updateMeal(Request $request){
    	$this->validate($request,[
                'date' => 'required',
                'breakfast' => 'required',
                'lunch' => 'required',
                'dinner' => 'required',
    
            ]);
            $id = $request->mealID;
            // dd($id);
            $month = date('M',strtotime($request->date));
            $meals = Meal::findOrFail($id);
            
    
            $meals->date = $request->date;
            $meals->month = $month;
            $meals->breakfast = $request->breakfast;
            $meals->lunch = $request->lunch;
            $meals->dinner = $request->dinner;
            $meals->status = "0";
    
            //  dd($meals);
            if ($meals->save()) {
                return response()->json("success");
             }
             else{
                return response()->json("error");
             }  
    }
}
