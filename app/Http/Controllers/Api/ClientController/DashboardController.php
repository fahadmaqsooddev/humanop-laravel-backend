<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Assessment;

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

            $assessment = Assessment::singleAssessment(Helpers::getUser()->id);
            $topThreeStyles = $assessment != null ? Assessment::getTopThreeStyles($assessment) : [];
            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
            $boundary = $assessment != null ? Assessment::getAlchemyPublicName($assessment) : [];
            $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
            $topTwoFeatures = $topFeatures != null ? CodeDetail::getPublicNames($topFeatures['top_two_keys']) : [];
            $topCommunication = $communication != null ? CodeDetail::getSinglePublicName($communication[0]) : [];

            $data = [
                'assessment' => $assessment,
                'topThreeStyles' => $topThreeStyles,
                'boundry' => $boundary,
                'topTwoFeatures' => $topTwoFeatures,
                'topCommunication' => $topCommunication
            ];

            return Helpers::successResponse('core stats', $data);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
