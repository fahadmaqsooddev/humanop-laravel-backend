<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
