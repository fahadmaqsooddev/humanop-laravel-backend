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
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Information\InformationIcon;
use App\Models\User;
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
            $daily_tip = DailyTip::getTodayTip();

            if ($daily_tip) {

                $trait = CodeDetail::getSinglePublicName($daily_tip['code']);

                $data = [
                    'title' => $daily_tip['title'],
                    'is_read' => $daily_tip['is_read'],
                    'description' => $daily_tip['description'],
                    'trait' => $trait ? $trait->public_name : null
                ];
            } else {

                $data = [];
            }

            return Helpers::successResponse('Daily Tip', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function latestPodcast(){

        try {

            $podcast = Podcast::getPodcast();

            return Helpers::successResponse('Podcast url', $podcast);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function coreStats(Request $request){

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

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function dailyTipRead(){

        try {

            DB::beginTransaction();

            $daily_tip_updated = UserDailyTip::readUserDailyTip();

            if (!$daily_tip_updated){

                $point = PointHelper::addPointsOnDailyTipRead();
            }

            DB::commit();

            return Helpers::successResponse('Daily tip read', ['point' => $point ?? 0]);

        }catch (\Exception $exception){

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function actionPlan(){

        try {

            $plan = ActionPlan::userActionPlan();

            return Helpers::successResponse('Action plan', $plan);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function informationIcon(){

        try {

            $info = InformationIcon::getInfo();

            return Helpers::successResponse('Information Icons', $info);

        }catch (\Exception $exception){

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

            }
            else
            {
                return Helpers::notFoundResponse('Assessment not found');
            }


        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
