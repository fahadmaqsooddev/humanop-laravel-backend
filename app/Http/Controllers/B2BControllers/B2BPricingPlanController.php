<?php

namespace App\Http\Controllers\B2BControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2BPricingPlanController extends Controller
{

    public function getB2BPricingPlan(){
        try {

            return view('b2b-dashboard/b2b-pricing-plans/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function getB2BCoupon(){
        try {

            return view('b2b-dashboard/b2b-coupons/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

}
