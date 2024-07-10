<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\AssessmentAnswersRequest;
use App\Models\Assessment;
use App\Models\AssessmentDetail;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function allAssessments(Request $request){

        try {

            $assessments = Assessment::assessmentsPaginated($request);

            return Helpers::successResponse('All assessments', $assessments, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function assessmentAnswers(AssessmentAnswersRequest $request){

        try {

            $assessment_answers = AssessmentDetail::assessmentAnswers($request, $request->input('assessment_id'));

            return Helpers::successResponse('Assessment Answers', $assessment_answers, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
