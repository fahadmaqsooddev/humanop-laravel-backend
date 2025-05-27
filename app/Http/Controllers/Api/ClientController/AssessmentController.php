<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\AssessmentAnswersRequest;
use App\Http\Requests\Api\Client\AssessmentSubmitRequest;
use App\Http\Requests\Api\Client\GridRequest;
use App\Http\Requests\Api\Client\QuestionsRequest;
use App\Http\Requests\Api\Client\UserReportRequest;
use App\Models\Admin\Pages\Page;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\AssessmentDetail;
use App\Models\Question;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function allAssessments(Request $request)
    {

        try {

            $assessments = Assessment::assessmentsPaginated($request);

            return Helpers::successResponse('All assessments', $assessments, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function assessmentAnswers(AssessmentAnswersRequest $request)
    {

        try {

            $assessment_answers = AssessmentDetail::assessmentAnswers($request, $request->input('assessment_id'));

            return Helpers::successResponse('Assessment Answers', $assessment_answers, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function grid(GridRequest $request)
    {

        try {

            $grid = Assessment::getGridForApi($request->input('assessment_id'));

            return Helpers::successResponse('User grid data', $grid);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function assessmentStatus()
    {

        try {

            $user = Helpers::getUser();

            $status = Assessment::assessmentStatusForApi();

            $assessment_price = StripeSetting::getSingle();

            $latest_assessment = Assessment::getLatestAssessment($user['id']);

            $assessment_count = Assessment::getAllAssessmentCount($user['id']);

            if (!empty($latest_assessment) && $latest_assessment['reset_assessment'] == 1) {

                return Helpers::successResponse('Reset Assessment', [
                    'latest_assessment_id' => $latest_assessment ? $latest_assessment['id'] : '',
                    'assessment_count' => $assessment_count,
                    'assessment_page_number' => $status,
                    'retake_assessment' => null,
                    'assessment_price' => ($assessment_price->amount ?? 0),
                    'user' => [
                        'last_four_digits' => $user['pm_last_four'],
                        'exp_month' => $user['pm_exp_month'],
                        'exp_year' => $user['pm_exp_year'],
                        'name' => $user['card_name'],
                    ]
                ]);
            } elseif (!empty($latest_assessment)) {

                $minutes = Helpers::explodeTimezoneWithHours($user['timezone']);

                $userTime = \Carbon\Carbon::parse($latest_assessment['updated_at'])->addMinutes($minutes * 60)->toDateTimeString();

                $difference = \Carbon\Carbon::now()->diffInDays($userTime);

                if ($difference <= 90) {

                    $takeAssessment = 90 - $difference;

                    return Helpers::successResponse('You can take another assessment after ' . $takeAssessment . ' days.', [
                        'latest_assessment_id' => $latest_assessment ? $latest_assessment['id'] : '',
                        'assessment_count' => $assessment_count,
                        'retake_assessment' => $takeAssessment,
                        'assessment_page_number' => $status,
                        'assessment_price' => ($assessment_price->amount ?? 0),
                        'user' => [
                            'last_four_digits' => $user['pm_last_four'],
                            'exp_month' => $user['pm_exp_month'],
                            'exp_year' => $user['pm_exp_year'],
                            'name' => $user['card_name'],
                        ]
                    ]);

                }
                else{
                    return Helpers::successResponse('Assessment Status', [
                        'latest_assessment_id' => $latest_assessment ? $latest_assessment['id'] : '',
                        'assessment_count' => $assessment_count,
                        'retake_assessment' => null,
                        'assessment_page_number' => $status,
                        'assessment_price' => ($assessment_price->amount ?? 0),
                        'user' => [
                            'last_four_digits' => $user['pm_last_four'],
                            'exp_month' => $user['pm_exp_month'],
                            'exp_year' => $user['pm_exp_year'],
                            'name' => $user['card_name'],
                        ]
                    ]);
                }
            } else {

                return Helpers::successResponse('Assessment Status', [
                    'latest_assessment_id' => $latest_assessment ? $latest_assessment['id'] : '',
                    'assessment_count' => $assessment_count,
                    'assessment_page_number' => $status,
                    'assessment_price' => ($assessment_price->amount ?? 0),
                    'user' => [
                        'last_four_digits' => $user['pm_last_four'],
                        'exp_month' => $user['pm_exp_month'],
                        'exp_year' => $user['pm_exp_year'],
                        'name' => $user['card_name'],
                    ]
                ]);
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function questions(QuestionsRequest $request)
    {

        try {

            $assessment = Assessment::where('user_id', Helpers::getUser()->id)->latest()->first();

            if ($assessment && ($assessment->page + 1 ?? 0) == $request->input('page')) {

                $questions = Question::paginatedQuestions();

                return Helpers::successResponse('Questions', $questions, true);

            } else {

                return Helpers::validationResponse('Invalid page number');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function submitAnswers(AssessmentSubmitRequest $request)
    {

        try {

            $message = Assessment::submitQuestionAnswers($request->input('answer_ids'));

            return Helpers::successResponse($message);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function userReport(UserReportRequest $request)
    {

        try {

            $reports = Assessment::getReport($request->input('assessment_id'));

            $alchemy_code = Assessment::getAlchlCode($request->input('assessment_id'));

            $style_position = AssessmentColorCode::getStylePosition($request->input('assessment_id'));

            $feature_position = AssessmentColorCode::getFeaturePosition($request->input('assessment_id'));

            $data = [
                'reports' => $reports,
                'alchemy_code' => $alchemy_code,
                'style_position' => $style_position,
                'feature_position' => $feature_position
            ];

            return Helpers::successResponse('User assessment report', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function introAssessment()
    {
        try {

            $introductionAssessment = Page::getSinglePage(14);

            return Helpers::successResponse('intro assessment', $introductionAssessment);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
