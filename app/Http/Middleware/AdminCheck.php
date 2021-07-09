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
        // dd($role_name);
        if($admin_role == 1 && $role_name == 'Admin'){
             return $next($request);
        }
        else{
            return redirect('/');
        }
    }
}
