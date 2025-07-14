<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;

class OptimizationPlanController extends Controller
{

    public function allOptimizationPlan()
    {
        try {

            return view('admin-dashboards.optimization-plan.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
