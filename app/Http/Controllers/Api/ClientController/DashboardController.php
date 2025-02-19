<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\Helpers;
use App\Helpers\Points\PointHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Notification\Notification;
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

            if (!empty($assessment))
            {
                $userDailyTip = UserDailyTip::getLatestTip();

                if ($userDailyTip) {

                    $isRead = $userDailyTip['is_read'];

                    $updatedWithinDay = $userDailyTip['updated_at'] >= now()->subDay();

                    if ($isRead == 0 || ($isRead == 1 && $updatedWithinDay)) {

                        $data = [
                            'title' => $userDailyTip['dailyTip']['title'] ?? '',
                            'description' => $userDailyTip['dailyTip']['description'] ?? '',
                            'is_read' => $isRead,
                            'created_at' => $isRead == 1 ? $userDailyTip['updated_at'] : null,
                        ];

                        return Helpers::successResponse('Daily Tip', $data);
                    }
                }

                do {

                    $randomCode = DailyTip::randomCode($assessment);

                    $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                    if ($newDailyTip) {

                        $latestTip = UserDailyTip::where('user_id', $user['id'])
                            ->where('daily_tip_id', $newDailyTip['id'])
                            ->latest()
                            ->first();

                        if (empty($latestTip)) {

                            $newUserDailyTip = UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $assessment['id']);

                            $message = 'Your New Daily Tip';

                            event(new NewDailyTip($user['id'], 'new daily tip', $message));
                            Helpers::OneSignalApiUsed($user['id'], 'new daily tip', $message);
                            Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION);

                            $data = [
                                'title' => $newUserDailyTip['dailyTip']['title'],
                                'description' => $newUserDailyTip['dailyTip']['description'],
                                'is_read' => $newUserDailyTip['is_read'],
                                'created_at' => $newUserDailyTip['is_read'] == 1 ? $newUserDailyTip['updated_at'] : null,
                            ];

                            return Helpers::successResponse('Daily Tip', $data);
                        }
                    }
                } while ($newDailyTip && $latestTip && $latestTip['is_read'] == 1 && $latestTip['updated_at'] >= now()->subYear());

                return Helpers::validationResponse('No new daily tip found.');

            }
            else
            {
                return Helpers::successResponse('No new daily tip found.');

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
