<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use App\Helpers\Points\PointHelper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

            if (Auth::user()['is_admin'] == 1 || Auth::user()['is_admin'] == 3  || Auth::user()['is_admin'] == 4)
            {


                if (Auth::user()['is_admin'] == 4)
                {

                    $user = Auth::user();

                    $plan = $user['plan_name'];

                    if($plan){

                        PointHelper::addPointsToLog($user['id'],$plan);

                    }
                }

                return $next($request);

            }else{
                return redirect()->route('client_dashboard');
            }
        }else{

            $admin = Cache::get('admin');

            if (($admin['is_admin'] ?? false) && ($admin['admin_id'] ?? null)) {

                $admin_user = User::whereId($admin['admin_id'])->first();

                Auth::login($admin_user);

                return redirect()->route('admin_all_users');
            }

            Auth::logout();

            return redirect('/login')->with(['success'=>'You\'ve been logged out.']);

        }
    }
}
