<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCheck
{
    /**
     * Handle an incoming request.
     * Only checks if the user is logged in (has session).
     * Role-based access control is handled at the controller level.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('member_id')) {
            return redirect('/');
        }

        if (session('user_status') != 1) {
            session()->flush();
            return redirect('/')->with('error', 'Your account is not active. Please contact the manager.');
        }

        return $next($request);
    }
}
