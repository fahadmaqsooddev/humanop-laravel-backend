<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\StripeSetting\StripeSetting;
use Stripe\StripeClient;

class BillingController extends Controller
{

    public function billing()
    {
        try {

            $user = User::getSingleUser(Auth::user()['id']);

            return view('client-dashboard.billing.index', compact('user'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
