<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{

    public function setting()
    {
        try {

            $user = Auth::user();

            return view('client-dashboard.setting.index', compact('user'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function deleteAccount(){

        try {

            User::whereId(Helpers::getWebUser()->id)->delete();

            Auth::guard('web')->logout();

            return response()->json(['data' => 'Successfully logged out'], 200);

        }catch (\Exception $exception){

            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

}
