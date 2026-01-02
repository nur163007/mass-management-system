<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller
{
    public function showLoginForm(){
        
        return view('auth.login');
    }

    public function loginCheck(Request $request){
        // return $request;
        $email = strtolower($request->email);
        if ($user = Member::where('email',$email)->first()) {
            // dd("ok");
//            $passInfo = PasswordChange::where('customer_id',$customer->id)->first();
//            $confirm = isset($passInfo) ? $passInfo->confirmation : '';
                if(Hash::check($request->password, $user->password)) {
                            // dd("ok");
                            // dd($user->full_name);
                            $logged_in_data = session([
                                'member_name' => $user->full_name,
                                'member_id' => $user->id,
                                'email'=> $email,
                                'role'=> $user->role_id,
                                'role_name'=> $user->role_name,
                                'user_status'=> $user->status,
                                'profile'=> $user->photo,
                                'phone'=> $user->phone_no,
                                'address'=> $user->address,

                            ]);
                            // return view('home.dashboard');
                           // Super Admin (1) and Manager (2) go to admin dashboard
                           if($user->role_id == 1 || $user->role_id == 2){
                            return redirect('admin/dashboard');
                            }
                           // User (3) goes to user dashboard
                           elseif ($user->role_id == 3 ) {
                            if($user->status == 1){
                                 return redirect('user/dashboard');
                            }
                            else{
                                return redirect('/')->with('error','Your account is not active, Please contact with manager.');
                              }
                             
                            }
                    }

                        else {
                            return back()->with('error','Wrong Password');
                        }
    }
        else {
            return back()->with('error','Please give the valid email');

        }
              
        
     }
                

    public function userLogout(){
        // dd("ok");
        $forget = session()->flush();
        // dd($forget);
        return redirect('/');
    }
}
