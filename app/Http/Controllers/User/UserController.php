<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function dashboard(){
        $now = Carbon::now()->isoFormat('MMM');
        // dd($now);  
        $id= session()->get('member_id');
        $meal = DB::select("SELECT * from meals where members_id = '$id' and status = '1' and month = '$now'");

                $br = 0;
                $ln = 0;
                $dn = 0;

                foreach($meal as $m){
                    $br += $m->breakfast;
                    $ln += $m->lunch;
                    $dn += $m->dinner;
                }

                $total_meal = $br +  $ln + $dn;
                // dd($total_meal);

        $payments = DB::select("SELECT * from payments where member_id = '$id' and status ='1' and month = '$now'");

                 $fund =0;
                foreach($payments as $pay){
                $fund += $pay->payment_amount; 
                }

        $all_meal = DB::select("SELECT  breakfast,lunch,dinner from meals where status ='1' and month ='$now'");
        // dd($all_meal);
                $b = 0;
                $l = 0;
                $d = 0;

                foreach($all_meal as $m){
                    $b += $m->breakfast;
                    $l += $m->lunch;
                    $d += $m->dinner;
                }

                $count_meal = $b +  $l + $d;
        // dd($count_meal);

        $all_expanse = DB::select("SELECT  total_amount from expanses where status ='1' and month ='$now'");
            // dd($all_expanse);
                $all_ex =0;
                foreach($all_expanse as $exp){
                $all_ex += $exp->total_amount; 
                }
                // dd($all_ex);

                $all_meal_rate = ($all_ex / $count_meal);
                $my_amount = ($total_meal * $all_meal_rate);
                // dd($my_amount);
                $cash = ($fund - $my_amount);

                // dd($payable);

        return view('userpanel.dashboard.user_dashboard',compact('total_meal','fund','cash'));
    }


    public function userRegistration(){
        return view('userpanel.register.registration');
    }

    public function register(Request $request){
    	// return $request;

    	 $this->validate($request,[
            'full_name' => 'required',
            'phone_no' => 'required',
            'email' => 'required',
            'password' => 'required',
            'photo' => 'required',
            'nid_photo' => 'required', 
            'address' => 'required',
        ]);

        $members = new Member;

        $date = Carbon::now()->format('his')+rand(1000,9999);
        // profile photo 
       
        if($image = $request->file('photo')){
            $extention = $request->file('photo')->getClientOriginalExtension();
            $imageName = $date.'.'.$extention;
            $path = public_path('uploads/members/profile');
            $image->move($path,$imageName);
            $members->photo = $imageName;     
        }  
        else{
            $members->photo = "null";  
        }
        // nid photo 
        if($image2 = $request->file('nid_photo')){
            $extention2 = $request->file('nid_photo')->getClientOriginalExtension();
            $imageName2 = $date.'.'.$extention2;
            $path2 = public_path('uploads/members/nid');
            $image2->move($path2,$imageName2);
            $members->nid_photo = $imageName2;
            // dd($request['photo']);
        }  
        else{
            $members->nid_photo = "null";
        }
        $members->full_name = $request->full_name;
        $members->phone_no = $request->phone_no;
        $members->address = $request->address;
        $members->email = $request->email;
        $members->password = Hash::make($request->password);
        $members->role_id = "0";
        $members->role_name = "User";
        $members->status = "0";
        
        // return ($members);
    
         if ($members->save()) {
           return redirect()->back()->with('success','Membership request successfully sent.');
         }
         else{
            return redirect()->back()->with('error','Something Error Found !, Please try again.');
           
         }    

    }
}
