<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::guard('api');
    }

    public function handle(Request $request, Closure $next)
    {
        $checkUser = Helpers::getUser();

        if ($checkUser) {

            $minutes = Helpers::explodeTimezoneWithHours($checkUser['timezone']);

            $currentTime = Carbon::now()->addMinutes($minutes * 60);

            Helpers::checkAndAddHumanOpPoints($checkUser, $currentTime);

            if ($checkUser['reset_password'] > 0) {

                User::resetPassword($checkUser['id']);

                return response()->json(['status' => 401, 'message' => 'User logged out successfully', 'result' => array('data' => $data = [])], config('httpstatuscodes.ok_status'));

            } else {

                return $next($request);

            }
        }
    }
}
