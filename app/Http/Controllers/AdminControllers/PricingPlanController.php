<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Client\Plan\Plan;

class PricingPlanController extends Controller
{
    public function getPricingPlan(){
        try {

            return view('admin-dashboards/pricing-plans/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function createPricingPlan(){
        try {

            return view('admin-dashboards/pricing-plans/create');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function editPricingPlan($id){
        try {

            $plan = Plan::getSingleB2BPlan($id);

            return view('admin-dashboards/pricing-plans/edit', compact('plan'));

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

}
