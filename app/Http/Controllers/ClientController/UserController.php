<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assessment;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function userDetail()
    {
        try {

            return view('client-dashboard.user.client_user_detail');

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userInfo()
    {
        try {

            $user = Auth::user();
            return view('client-dashboard.user.client_user_info', compact('user'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function answers()
    {
        try {

            return view('client-dashboard.user.client_answer');

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function grid()
    {
        try {

            $grid = Assessment::getGrid();

            return view('client-dashboard.user.client_grid', compact('grid'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
