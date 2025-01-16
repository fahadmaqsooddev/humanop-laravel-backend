<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Helpers\Points\PointHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Information\InformationIcon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function dailyTip()
    {
        try {
            $user = Helpers::getWebUser() ?? Helpers::getUser();

            $assessment = Assessment::getLatestAssessment($user['id']);

            $daily_tip = DailyTip::checkTodayTip($assessment['id']);

            if ($daily_tip === false) {
                do {
                    $randomCode = DailyTip::randomCode($assessment);

                    $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                    if ($newDailyTip) {
                        $latestTip = UserDailyTip::where('user_id', $user['id'])
                            ->where('daily_tip_id', $newDailyTip['id'])
                            ->latest()
                            ->first();

                        // Check if the tip already exists within the past 365 days
                        if (empty($latestTip) || $latestTip->created_at < Carbon::now()->subDays(365)) {

                            $newUserDailyTip = UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $assessment['id']);

                            $data = [
                                'title' => $newUserDailyTip['dailyTip']['title'],
                                'is_read' => $newUserDailyTip['is_read'],
                                'description' => $newUserDailyTip['dailyTip']['description'],
                                'trait' => null,
                                'created_at' => $newUserDailyTip['created_at'],
                            ];

                            return Helpers::successResponse('Daily Tip', $data);
                        }
                    }
                } while ($newDailyTip && $latestTip && $latestTip->created_at >= Carbon::now()->subDays(365)); // Retry if the tip exists
            } else {

                $userDailyTipData = UserDailyTip::userDailytip($daily_tip['id']);

                $data = [
                    'title' => $daily_tip['title'],
                    'is_read' => $userDailyTipData['is_read'],
                    'description' => $daily_tip['description'],
                    'trait' => null,
                    'created_at' => $userDailyTipData['created_at'],
                ];

                return Helpers::successResponse('Daily Tip', $data);
            }
        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function haiChatStatus()
    {
        try {
            $user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;
            $haiStatus = User::checkHaiStatus($user_id);

            if ($haiStatus) {
                $data = [
                    'status' => $haiStatus
                ];
            } else {
                $data = [
                    'status' => false
                ];
            }
            return Helpers::successResponse('HAI CHAT status fetched successfully', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function latestPodcast()
    {

        try {

            $podcast = Podcast::getPodcast();

            return Helpers::successResponse('Podcast url', $podcast);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function coreStats(Request $request)
    {

        try {

            $interval_of_life = User::getUserAge(Helpers::getUser()->date_of_birth);

            $assessment = Assessment::singleAssessmentFromId($request->input('assessment_id', null));

            $topThreeStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];

            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];

            $boundary = $assessment != null ? Assessment::getAlchemyDetail($assessment) : null;

            $communication = $assessment != null ? Assessment::getEnergy($assessment) : null;

            $perception_life = CodeDetail::getPerceptionStaticText();

            $perception = $assessment != null ? Assessment::getPreceptionReportDetail($assessment) : null;

            $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];

            $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication, $assessment) : [];

            $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : null;

            $data = [
                'assessment' => $assessment,
                'topThreeStyles' => $topThreeStyles,
                'boundary' => $boundary,
                'topTwoFeatures' => $topTwoFeatures,
                'topCommunication' => $topCommunication,
                'energyPool' => $energyPool,
                'your_perception' => $perception_life,
                'perception' => $perception,
                'interval_of_life' => $interval_of_life
            ];

            return Helpers::successResponse('core stats', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function dailyTipRead()
    {

        try {

            DB::beginTransaction();

            $daily_tip_updated = UserDailyTip::readUserDailyTip();

            if (!$daily_tip_updated) {
                $point = PointHelper::addPointsOnDailyTipRead();
            }

            DB::commit();

            return Helpers::successResponse('Daily tip read', ['point' => $point ?? 0]);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function actionPlan()
    {

        try {

            $user = Helpers::getUser();

            $assessment = Assessment::getLatestAssessment($user['id']);

            ActionPlan::checkUserActionPlan($assessment);

            $plan = ActionPlan::userActionPlan();

            return Helpers::successResponse('Action plan', $plan);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function informationIcon()
    {

        try {

            $info = InformationIcon::getInfo();

            return Helpers::successResponse('Information Icons', $info);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function optionalTrait()
    {
        try {

            $user = Helpers::getUser();

            $assessment = Assessment::getLatestAssessment($user['id']);

            if (!empty($assessment)) {

                $timezone = $user['timezone'];

                $topThreeStyles = Assessment::getAllStyles($assessment);

                $topFeatures = Assessment::getFeatures($assessment);

                $topTwoFeatures = Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment);

                $optionalTrait = Helpers::getOptionalTrait($timezone, $topThreeStyles, $topTwoFeatures);

                $optionalTraitDetail = CodeDetail::getOptionalTraitDetail($optionalTrait);

                return Helpers::successResponse('optional trait', $optionalTraitDetail);

            } else {
                return Helpers::notFoundResponse('Assessment not found');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
