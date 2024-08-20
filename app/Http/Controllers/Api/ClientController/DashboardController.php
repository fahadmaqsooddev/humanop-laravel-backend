<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\Podcast\Podcast;

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
}
