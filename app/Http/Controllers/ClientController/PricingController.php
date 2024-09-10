<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use App\Models\Client\Plan\Plan;
use Illuminate\Http\Request;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\User;
use App\Helpers\Helpers;

class PricingController extends Controller
{

    public function index()
    {
        try {

            $user = Helpers::getWebUser();

            $stripe_setting = StripeSetting::getSingle();

            $plans = Plan::all();

            return view('client-dashboard.pricing.index', compact('user', 'stripe_setting', 'plans'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
