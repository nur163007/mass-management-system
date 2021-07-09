<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index(){
    	$id = session()->get('member_id');
    	// dd($id);
    	$profile = DB::table('members')->select('*')->where('id',$id)->first();
    	// dd($profile);
        return view('userpanel.profile.user_profile',compact('profile'));
    }

  public function updateProfile(Request $request){
  	// dd($request);
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
                return redirect('user/profile/viewProfile');
             }
     }
 
}
