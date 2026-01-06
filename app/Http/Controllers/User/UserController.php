<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\SmartAnalyticsService;
use App\Services\SmartNotificationService;


class UserController extends Controller
{
    protected $analyticsService;
    protected $notificationService;

    public function __construct(
        SmartAnalyticsService $analyticsService,
        SmartNotificationService $notificationService
    ) {
        $this->analyticsService = $analyticsService;
        $this->notificationService = $notificationService;
    }

    public function dashboard(){
        $now = Carbon::now()->format('F'); // Full month name (January, February, etc.)
        $id = session()->get('member_id');
        
        // Get smart balance
        $balance = $this->analyticsService->getMemberBalance($id, $now);
        
        // Get notifications
        $notifications = $this->notificationService->getUserNotifications($id);
        
        // Calculate total meal for logged-in user
        $meal = DB::select("SELECT * from meals where members_id = ? and status = '1' and month = ?", [$id, $now]);

        $br = 0;
        $ln = 0;
        $dn = 0;

        foreach($meal as $m){
            $br += $m->breakfast;
            $ln += $m->lunch;
            $dn += $m->dinner;
        }

        $total_meal = $br +  $ln + $dn;

        // Calculate total payment - only food_advance payments
        $payments = DB::select("SELECT * from payments where member_id = ? and status ='1' and payment_type = 'food_advance' and month = ?", [$id, $now]);

        $fund = 0;
        foreach($payments as $pay){
            $fund += $pay->payment_amount; 
        }

        // Calculate meal rate from all meals and expanses (excluding Super Admin)
        $all_meal = DB::select("SELECT meals.breakfast, meals.lunch, meals.dinner 
            from meals 
            INNER JOIN members ON meals.members_id = members.id 
            where meals.status ='1' and members.role_id != 1 and meals.month = ?", [$now]);
        
        $b = 0;
        $l = 0;
        $d = 0;

        foreach($all_meal as $m){
            $b += $m->breakfast;
            $l += $m->lunch;
            $d += $m->dinner;
        }

        $count_meal = $b +  $l + $d;

        // Get expanses excluding Super Admin
        $all_expanse = DB::select("SELECT expanses.total_amount 
            from expanses 
            INNER JOIN members ON expanses.member_id = members.id 
            where expanses.status ='1' and members.role_id != 1 and expanses.month = ?", [$now]);
        
        $all_ex = 0;
        foreach($all_expanse as $exp){
            $all_ex += $exp->total_amount; 
        }

        $all_meal_rate = $count_meal > 0 ? ($all_ex / $count_meal) : 0;
        $my_amount = ($total_meal * $all_meal_rate);
        
        // Meal due = meal cost - food_advance payment (if negative, show as due)
        $meal_due = $my_amount - $fund;
        
        // Cashback = food_advance payment - meal cost (if positive)
        $cashback = $fund > $my_amount ? ($fund - $my_amount) : 0;

        return view('userpanel.dashboard.user_dashboard', compact(
            'total_meal',
            'fund',
            'meal_due',
            'cashback',
            'balance',
            'notifications'
        ));
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nid_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
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
        // All registrations default to User (role_id = 3)
        $members->role_id = 3;
        $members->role_name = "User";
        // New users are pending approval
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
