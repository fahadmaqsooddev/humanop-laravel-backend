<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Helpers\Points\PointHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\DailyTip\DailyTip;
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

    public function dailyTip(){
        try {

//            $daily_tip = DailyTip::dailyTip();
            $daily_tip = DailyTip::getTodayTip();

            if($daily_tip) {
                $traitDescription = CodeDetail::getSinglePublicName($daily_tip['code'])->public_name;
                $daily_tip = $daily_tip['description'];
            }else{
                $daily_tip = '';
                $traitDescription = '';
            }

            $data = ['daily_tip' => $daily_tip,'trait' => $traitDescription];

            return Helpers::successResponse('Daily Tip', $data);
        }catch (\Exception $exception){
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

            $energyPool = $assessment != null ? Assessment::getEnergyPoolDetail($assessment) : null;

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

            $daily_tip_updated = DailyTip::readUserDailyTip();

            if (!$daily_tip_updated){

                PointHelper::addPointsOnDailyTipRead();
            }

            DB::commit();

            return Helpers::successResponse('Daily tip read');

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
}
