<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;

class AssessmentWalkthrough extends Controller
{

    public function getWalkThrough()
    {
        try {

            return view('admin-dashboards.walkthrough.assessment-walkthrough');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
