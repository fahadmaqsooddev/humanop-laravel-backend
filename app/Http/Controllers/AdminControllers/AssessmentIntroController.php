<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\AssessmentIntro\AssessmentIntro;

class AssessmentIntroController extends Controller
{
    //
    public function ManageAssessmentIntro()
    {
        try {

            $assessmentIntro = AssessmentIntro::allIntro();

            return view('admin-dashboards.assessment-intro.index', compact('assessmentIntro'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }

    public function createAssessmentIntro(){
        try {
            return view('admin-dashboards.assessment-intro.create');

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }


    public function editAssessmentIntro($id)
    {
        try {

            $assessment = AssessmentIntro::getSingleAssessmentIntro($id);

            return view('admin-dashboards.assessment-intro.edit', compact('assessment'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }

}
