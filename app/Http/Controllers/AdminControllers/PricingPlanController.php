<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PricingPlanController extends Controller
{
    public function getPricingPlan(){
        try {

            return view('admin-dashboards/pricing-plans/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }
}
