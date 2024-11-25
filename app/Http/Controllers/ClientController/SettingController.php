<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client\Plan\Plan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{

    public function setting()
    {
        try {

            $user = Helpers::getWebUser();

            $subscription = $user->subscription('main');

            $payment_history = Payment::getPaymentHistory();

            return view('client-dashboard.setting.index', compact('user','subscription','payment_history'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function deleteAccount(){

        try {

            User::whereId(Helpers::getWebUser()->id)->delete();

            Auth::guard('web')->logout();

            Session::flush();

            Cookie::forget("email");
            Cookie::forget("password");

            Cache::forget('admin');

            return response()->json(['data' => 'Successfully logged out'], 200);

        }catch (\Exception $exception){

            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

}
