<?php

namespace App\Http\Controllers;

use App\Models\Admin\DailyTip\DailyTip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


class SessionController extends Controller
{
    public function create()
    {
        if (Auth::check())
        {
            if (Auth::user()['is_admin'] == '1')
            {
                return redirect()->route('admin_dashboard');
            }else{
                return redirect()->route('client_dashboard');
            }
        }else{
            return view('session/login');
        }

    }

    public function store(Request $request)
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if(Auth::attempt($attributes))
        {
            if (isset($request['remember']) && !empty($request['remember']))
            {
                setcookie("email", $attributes['email'], 30*time()+3600);
                setcookie("password", $attributes['password'], 30*time()+3600);
            }else
            {
                setcookie("email", "");
                setcookie("password", "");
            }

            DailyTip::updateUserDailyTip();

            User::updateUserIsFeedback();

            return redirect()->route('admin_dashboard');
        }

        return back()->withErrors(['msgError' => 'These credentials do not match our records.']);
    }

    public function destroy()
    {

        Auth::logout();

        return redirect('/login')->with(['success'=>'You\'ve been logged out.']);
    }
}
