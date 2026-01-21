<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_role = session('role');
        $user_status = session('user_status');
        // Allow Manager (role_id = 2) and User (role_id = 3) to access user routes
        if(($user_role == 2 || $user_role == 3) && $user_status == 1){
            // dd("ok");
             return $next($request);
        }
        else{
            return redirect('/');
        }
       
    }
}
