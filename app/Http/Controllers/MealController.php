<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Member;
use Illuminate\Http\Request;
use DB;

class MealController extends Controller
{
    public function addMeal(){
        $members = Member::all();
        return view('admin.meal.addMeal',compact('members'));
    }

    public function storeMeal(Request $request){
        // dd($request);
        $this->validate($request,[
            'members_id' => 'required',
            'date' => 'required',
            'breakfast' => 'required',
            'lunch' => 'required',
            'dinner' => 'required',

        ]);
        
        $month = date('M',strtotime($request->date));
        $convert_date = date('Y-m-d',strtotime($request->date));
        $meals = new Meal();

        $meals->members_id = $request->members_id;
        $meals->date = $convert_date;
        $meals->month = $month;
        $meals->breakfast = $request->breakfast;
        $meals->lunch = $request->lunch;
        $meals->dinner = $request->dinner;

        //  dd($meals);
        if ($meals->save()) {
            return response()->json($meals);
         }
         else{
            return response()->json("error");
         }  
    }

    public function viewMeal(){
        return view('admin.meal.viewMeal');
    }
    public function showMeal(){
        $meals = DB::select("SELECT meals.id,meals.members_id,SUM(meals.breakfast + meals.lunch + meals.dinner) as total_meal, meals.month,m.full_name
        FROM meals
        INNER JOIN members m ON meals.members_id = m.id GROUP BY meals.members_id,meals.month");
        // dd($meals);
        return response()->json($meals);
    }


    public function mealDetails($member_id){
        // dd($member_id);
        $meals = DB::table('meals')->join('members','meals.members_id','members.id')->select('meals.*')->where('meals.members_id',$member_id)->get();
        //    dd($meals);

        $members = DB::table('meals')->join('members','meals.members_id','members.id')->select('members.*','meals.*')->where('meals.members_id',$member_id)->first();
        //    dd($members);

        $total = DB::select("SELECT SUM(breakfast+lunch+dinner)as total_meal from meals where members_id = '$member_id'");
        // dd($total);
      
        
        return view('admin.meal.mealDetails',compact('meals','members','total'));

        }


    public function deleteMeal($id){
        // dd($id);
        $meals = Meal::findOrFail($id);
           if($meals){     
               $meals->delete(); 
        return redirect()->back()->with('success','Meal successfully deleted.');
    }else{
        return redirect()->back()->with('error','Something Error Found !, Please try again.');
    }
        }

        public function editMeal($id){
            // $meals = Meal::findOrFail($id);
            $meals = DB::table('meals')->join('members','meals.members_id','members.id')->select('meals.*','members.full_name')->where('meals.id',$id)->first();
    //    dd($meals);
            return view('admin.meal.editMeal',compact('meals'));
        }

        public function updateMeal(Request $request){
            // dd($request)->all();
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
    
            //  dd($meals);
            if ($meals->save()) {
                return response()->json("success");
             }
             else{
                return response()->json("error");
             }  
        }

        
    
}
