<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

    public function store()
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(Auth::attempt($attributes))
        {
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
