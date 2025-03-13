<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssessmentWalkthrough extends Controller
{
    //

    public function getWalkThrough()
    {
        try {

            return view('admin-dashboards.walkthrough.assessment-walkthrough');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
