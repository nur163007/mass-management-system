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
                            $roleName = $user->role ? $user->role->name : $user->role_name;

                            $logged_in_data = session([
                                'member_name' => $user->full_name,
                                'member_id' => $user->id,
                                'email'=> $email,
                                'role'=> $user->role_id,
                                'role_name'=> $roleName,
                                'user_status'=> $user->status,
                                'profile'=> $user->photo,
                                'phone'=> $user->phone_no,
                                'address'=> $user->address,

                            ]);
                            // return view('home.dashboard');
                            if ($user->status != 1) {
                                session()->flush();
                                return redirect('/')->with('error', 'Your account is not active, Please contact with manager.');
                            }

                            return redirect()->route('dashboard');
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
