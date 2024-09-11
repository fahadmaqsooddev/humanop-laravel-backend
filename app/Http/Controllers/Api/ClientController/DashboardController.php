<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Helpers\Points\PointHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function dailyTip(){

        try {

            $daily_tip = DailyTip::dailyTip();

            return Helpers::successResponse('Daily Tip', $daily_tip);

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

    public function coreStats(){

        try {
            $user_age = User::getUserAge(Helpers::getUser()->age_group);
            $assessment = Assessment::getLatestAssessment(Helpers::getUser()->id);
            $topThreeStyles = $assessment != null ? Assessment::getTopThreeStyles($assessment) : [];
            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
            $boundary = $assessment != null ? Assessment::getAlchemyPublicName($assessment) : [];
            $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
            $perception = $assessment != null ? Assessment::getPreceptionReport($assessment) : [];
            $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
            $topCommunication = $communication != null ? CodeDetail::getCommunicationPublicName($communication) : [];
            $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : [];

            $data = [
                'assessment' => $assessment,
                'topThreeStyles' => $topThreeStyles,
                'boundry' => $boundary,
                'topTwoFeatures' => $topTwoFeatures,
                'topCommunication' => $topCommunication,
                'perception' => $perception,
                'energyPool' => $energyPool,
                'userAge' => $user_age,
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
}
