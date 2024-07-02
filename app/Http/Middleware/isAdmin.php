<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check())
        {

            if (Auth::user()['is_admin'] == 1 || Auth::user()['is_admin'] == 3)
            {
                return $next($request);
            }else{
                return redirect()->route('client_dashboard');
            }
        }else{

            Auth::logout();

            return redirect('/login')->with(['success'=>'You\'ve been logged out.']);

        }
    }
}
