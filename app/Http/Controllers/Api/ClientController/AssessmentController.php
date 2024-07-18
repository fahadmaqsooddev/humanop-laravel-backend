<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\AssessmentAnswersRequest;
use App\Http\Requests\Api\Client\GridRequest;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Assessment;
use App\Models\AssessmentDetail;
use App\Models\Question;
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

    public function grid(GridRequest $request){

        try {

            $grid = Assessment::getGridForApi($request->input('assessment_id'));

            return Helpers::successResponse('User grid data', $grid);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function assessmentStatus(){

        try {

            $status = Assessment::assessmentStatusForApi();

            $assessment_price = StripeSetting::getSingle();

            $data = [
                'assessment_page_number' => $status,
                'assessment_price' => ($assessment_price->amount ?? 0)
            ];

            return Helpers::successResponse('Assessment Status', $data);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function questions(){

        try {

            $questions = Question::paginatedQuestions();

            return Helpers::successResponse('Questions', $questions, true);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
