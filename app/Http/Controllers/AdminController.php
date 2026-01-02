<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use Carbon\Carbon;
use App\Services\SmartAnalyticsService;
use App\Services\MemberBalanceService;
use App\Services\SmartNotificationService;

class AdminController extends Controller
{
    protected $analyticsService;
    protected $balanceService;
    protected $notificationService;

    public function __construct(
        SmartAnalyticsService $analyticsService,
        MemberBalanceService $balanceService,
        SmartNotificationService $notificationService
    ) {
        $this->analyticsService = $analyticsService;
        $this->balanceService = $balanceService;
        $this->notificationService = $notificationService;
    }

    public function dashboard(){
        $now = Carbon::now()->isoFormat('MMM');

        // Get smart analytics
        $analytics = $this->analyticsService->getDashboardAnalytics($now);
        
        // Get member balances
        $memberBalances = $this->balanceService->getAllMembersBalance($now);
        $membersSummary = $this->balanceService->getMembersSummary($now);
        
        // Get optimization suggestions
        $suggestions = $this->analyticsService->getOptimizationSuggestions($now);
        
        // Get notifications
        $notifications = $this->notificationService->getAdminNotifications();
        
        // Legacy data for backward compatibility - Exclude Super Admin (role_id = 1), only count Manager (2) and User (3)
        $members = DB::select("SELECT count('id')as total_member from members where status = '1' AND role_id IN (2, 3)");

        // Extract individual variables for backward compatibility with existing views
        $total_meal = $analytics['current']['total_meal'];
        $fund = $analytics['current']['fund'];
        $all_ex = $analytics['current']['expense'];
        $meal_rate = $analytics['current']['meal_rate'];
        $cash = $analytics['current']['cash'];

        return view('admin.dashboard', compact(
            'analytics',
            'memberBalances',
            'membersSummary',
            'suggestions',
            'notifications',
            'members',
            // Legacy variables for backward compatibility
            'total_meal',
            'fund',
            'all_ex',
            'meal_rate',
            'cash'
        ));
    }

    public function viewProfile(){
    	$id = session()->get('member_id');
    	// dd($id);
    	$profile = DB::table('members')->select('*')->where('id',$id)->first();
    	// dd($profile);
        return view('admin.profile.admin_profile',compact('profile'));
    }


    public function updateProfile(Request $request){
  	// return($request);
  	$id = $request->memberId;
  	// dd($id);
  	$member = Member::findOrFail($id);

  	$member->full_name = $request->name;
  	$member->email = $request->email;
  	$member->phone_no = $request->phone;
  	$member->address = $request->address;
// dd($member);
  	session([ 'member_name' => $request->name]);
  	if($member->save()){
  		return redirect()->back()->with('success','Profile successfully updated.');
  	}
  	else{
            return redirect()->back()->with('error','Something Error Found !, Please try again.');
        }
  }

 	public function updatePassword(Request $request){
		   $id = $request->mID;
		  	// dd($id);
		  	$password = Member::findOrFail($id);

		  	$old_pass = $password->password;
		  	// dd($old_pass);
		  	$current_pass =$request->old_pass;
		  	$new_pass = $request->new_pass;
		  	$c_pass = $request->confirm_pass;

		  	if(Hash::check($current_pass, $old_pass)){
		  		// dd("ok");
		  		if ($new_pass == $c_pass) {
		  				$password->password = Hash::make($new_pass);
		  				$password->save();

		  				session()->flush();
		  				return redirect('/');
		  			}
		  			else{
		  				return redirect()->back()->with('error','Password not matched');
		  			}	
		  	}
		  	else{
            return redirect()->back()->with('error','Previous password incorrect');
        }

  }

  public function updatePicture(Request $request){
  	// dd("ok");
  	  		$id = $request->photoId;
		  	// dd($id);
		  	$photo = Member::findOrFail($id);

		  	$date = Carbon::now()->format('his')+rand(1000,9999);
    
            // profile photo 
            
            if($image = $request->file('photo')){
                $extention = $request->file('photo')->getClientOriginalExtension();
                $imageName = $date.'.'.$extention;
                $path = public_path('uploads/members/profile');
                $image->move($path,$imageName);

                if(file_exists('uploads/members/profile/'.$photo->photo) AND !empty($photo->photo)){
                    unlink('uploads/members/profile/'.$photo->photo);
                }

                $photo->photo = $imageName;
                session([ 'profile'=> $imageName]);
            }  
            else{
                
                $photo->photo = $photo->photo;
            }

            if ($photo->save()) {
                return redirect('admin/profile/view-profile');
             }
     }

}
