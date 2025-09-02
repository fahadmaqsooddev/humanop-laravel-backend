<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\AssessmentAnswersRequest;
use App\Http\Requests\Api\Client\AssessmentSubmitRequest;
use App\Http\Requests\Api\Client\AssessmentVideoTrackRequest;
use App\Http\Requests\Api\Client\GridRequest;
use App\Http\Requests\Api\Client\QuestionsRequest;
use App\Http\Requests\Api\Client\UserReportRequest;
use App\Models\Admin\AssessmentIntro\AssessmentIntro;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\Pages\Page;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\AssessmentDetail;
use App\Models\AssessmentVideoTrack;
use App\Models\Client\PurchasedItems;
use App\Models\Question;
use App\Models\User;
use App\Models\Videos\VideoProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Stripe;

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
                    'plan_name' => $user['plan_name'],
                    'retake_assessment' => null,
                    'assessment_price' => ($assessment_price->amount ?? 0),
                    'user' => [
                        'last_four_digits' => $user['pm_last_four'],
                        'exp_month' => $user['pm_exp_month'],
                        'exp_year' => $user['pm_exp_year'],
                        'name' => $user['card_name'],
                    ]
                ]);

            } elseif ($user['plan_name'] != 'Freemium') {

                return Helpers::successResponse('Assessment Status', [
                    'latest_assessment_id' => $latest_assessment ? $latest_assessment['id'] : '',
                    'assessment_count' => $assessment_count,
                    'assessment_page_number' => $status,
                    'plan_name' => $user['plan_name'],
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
                        'plan_name' => $user['plan_name'],
                        'assessment_page_number' => $status,
                        'assessment_price' => ($assessment_price->amount ?? 0),
                        'user' => [
                            'last_four_digits' => $user['pm_last_four'],
                            'exp_month' => $user['pm_exp_month'],
                            'exp_year' => $user['pm_exp_year'],
                            'name' => $user['card_name'],
                        ]
                    ]);

                } else {
                    return Helpers::successResponse('Assessment Status', [
                        'latest_assessment_id' => $latest_assessment ? $latest_assessment['id'] : '',
                        'assessment_count' => $assessment_count,
                        'retake_assessment' => null,
                        'plan_name' => $user['plan_name'],
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
                    'plan_name' => $user['plan_name'],
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

//        try {

            $message = Assessment::submitQuestionAnswers($request->input('answer_ids'));

            return Helpers::successResponse($message);

//        } catch (\Exception $exception) {
//
//            return Helpers::serverErrorResponse($exception->getMessage());
//        }

    }

    public function createStylesAssessment()
    {

        try {

            $existingAssessment = Assessment::getLatestAssessment(Helpers::getUser()['id']);

            $createStyle = AssessmentColorCode::createStylesCodeAndColor($existingAssessment);

            return Helpers::successResponse($createStyle);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function assessmentWatchVideoTrack(AssessmentVideoTrackRequest $request)
    {
        try {

            $data = AssessmentVideoTrack::createOrUpdateAssessmentVideoTrack($request);

            VideoProgress::updateVideoProgress($data->assessment_id, $data->video_name, ['watch_time' => $data->video_time]);

            return Helpers::successResponse('Assessment video track.', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function getAssessmentVideoTrack()
    {
        try {

            $user = Helpers::getUser();

            $records = AssessmentVideoTrack::getAssessmentVideoTrack($user['id']);

            $trackRecords = [];

            foreach ($records as $record) {

                $data = AssessmentIntro::getRecord($record['video_name']);

                $codeDetail = CodeDetail::getRecord($record['video_name']);

                $interval_life = User::getUserAgeRecord($user['date_of_birth'], $record['assessment_id']);

                if (!empty($data)) {

                    $video = $data->video ?? null;

                    $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
                        ? $video['video_upload_url']['path']
                        : ($video['video_url'] ?? null);

                    $progress = VideoProgress::checkVideoProgress($record['assessment_id'], $data->name);

                    $trackRecords[] = [
                        'assessment_id' => $record['assessment_id'],
                        'user_id' => $record['user_id'],
                        'video_name' => $data->name,
                        'public_name' => $data->public_name,
                        'description' => $data->text,
                        'video_url' => $videoUrl,
                        'video_progress' => $progress,
                        'video_time' => $record['video_time'],
                    ];

                } elseif (!empty($codeDetail)) {

                    $video = $codeDetail->video ?? null;

                    $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
                        ? $video['video_upload_url']['path']
                        : ($video['video_url'] ?? null);

                    $progress = VideoProgress::checkVideoProgress($record['assessment_id'], $codeDetail->name);

                    $trackRecords[] = [
                        'assessment_id' => $record['assessment_id'],
                        'user_id' => $record['user_id'],
                        'video_name' => $codeDetail->name,
                        'public_name' => $codeDetail->public_name,
                        'description' => $codeDetail->text,
                        'video_url' => $videoUrl,
                        'video_progress' => $progress,
                        'video_time' => $record['video_time'],
                    ];

                } elseif ($interval_life['name'] == $record['video_name']) {

                    $trackRecords[] = [
                        'assessment_id' => $record['assessment_id'],
                        'user_id' => $record['user_id'],
                        'video_name' => $interval_life['name'],
                        'public_name' => $interval_life['public_name'],
                        'description' => $interval_life['description'],
                        'video_url' => $interval_life['video_url'],
                        'video_progress' => $interval_life['video_progress'],
                        'video_time' => $record['video_time'],
                    ];
                }

            }

            return Helpers::successResponse('Assessment video track data.', $trackRecords);

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

    public function assessmentCheckout(Request $request)
    {
        try {

            DB::beginTransaction();

            $user = Helpers::getUser();

            $latest_assessment = Assessment::getLatestAssessment($user['id']);

            $minutes = Helpers::explodeTimezoneWithHours($user['timezone']);

            $userTime = Carbon::parse($latest_assessment['updated_at'])->addMinutes($minutes * 60)->toDateTimeString();

            $difference = Carbon::now()->diffInDays($userTime);

            if (($user['plan_name'] != 'Freemium')) {

                Stripe::setApiKey(config('cashier.secret'));

                $charge = Charge::create([
                    "amount" => $request['amount'] * 100,
                    "currency" => "usd",
                    "source" => 'tok_visa',
                    "description" => "Retake Assessment Payment"
                ]);

                if ($charge && $charge->status === 'succeeded') {

                    $name = "You have purchased Paid Assessment";

                    PurchasedItems::createItem($user['id'], $name, $request['price'], Admin::B2C_PURCHASED_ITEM);

                    DB::commit();

                    return Helpers::successResponse("Payment completed successfully! You can now retake assessment.");

                } else {

                    return Helpers::validationResponse("Payment failed. Please try again.");

                }

            } else {

                $takeAssessment = 90 - $difference;

                return Helpers::validationResponse('You can take another assessment after ' . $takeAssessment . ' days.');
            }

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
