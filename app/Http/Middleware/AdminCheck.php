<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminCheck
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
        $admin_role = session('role');
        $role_name = session('role_name');
        // Super Admin (1) and Manager (2) can access admin panel
        if(($admin_role == 1 && $role_name == 'Super Admin') || ($admin_role == 2 && $role_name == 'Manager')){
             return $next($request);
        }
        else{
            return redirect('/');
        }
    }
}
