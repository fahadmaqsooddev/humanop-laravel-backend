<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::guard('api');
    }

    public function handle(Request $request, Closure $next)
    {
        $user = Helpers::getUser();

        if ($user) {
            if ($user['reset_password'] > 0 ) {

//                $this->auth->logout();

                User::resetPassword($user['id']);

                return response()->json(['status' => 401, 'message' => 'User logged out successfully', 'result' => array('data' => $data = [])], config('httpstatuscodes.ok_status'));

            } else {

                return $next($request);

            }
        }
    }
}
