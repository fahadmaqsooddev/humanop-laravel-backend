<?php

namespace App\Helpers\Assessments;

use App\Models\Assessment;
use Illuminate\Support\Facades\Auth;

class AssessmentHelper
{
    public static function getAssessments()
    {
//        $user_id = Auth::user()['id'] ;

        $assessments = Assessment::getAssessmentIds();

        return $assessments;
    }

}
