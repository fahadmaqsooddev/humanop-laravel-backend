<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use App\Models\Client\Plan\Plan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Points\PointHelper;
use Illuminate\Support\Facades\Log;

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

        Log::info(['client mid' => Auth::check()]);

        if (Auth::check())
        {
            if (Auth::user()['is_admin'] == 2)
            {

                    $user = Helpers::getWebUser();

                    $user_id = $user->id;

                    $plan = $user['plan_name'];


                    if($plan){

                        PointHelper::addPointsToLog($user_id,$plan);

                    }

                return $next($request);
            }else{
                return redirect()->route('admin_dashboard');
            }

        }else{

            Auth::logout();

            return redirect('/login')->with(['error'=>'You\'ve not logged in.']);

        }
    }
}
