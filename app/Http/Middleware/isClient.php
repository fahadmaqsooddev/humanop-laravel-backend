<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Points\PointHelper;
class isClient
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
            if (Auth::user()['is_admin'] == 2)
            {
                PointHelper::addPointsToLog(Auth::user()['id'],1);
                return $next($request);
            }else{
                return redirect()->route('admin_dashboard');
            }

        }else{

            Auth::logout();

            return redirect('/login')->with(['error'=>'You\'ve not loged in.']);

        }
    }
}
