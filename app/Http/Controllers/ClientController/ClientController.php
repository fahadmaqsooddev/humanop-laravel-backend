<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\Points\PointHelper;
use App\Http\Controllers\Controller;
use App\Models\Client\Point\PointLog;
use App\Models\HAIChai\QueryAnswer;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\TipRecord;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Assessment;
use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{

    public function index()
    {
       try {

            $user_age = User::getUserAge(Helpers::getWebUser()->age_group);
            $podcast = Podcast::getPodcast();
            $user = Helpers::getWebUser();
            $tip = DailyTip::getSingleTip();
            $admin_answer = QueryAnswer::userQueryAnswer();
            $assessment = Assessment::getLatestAssessment($user['id']);
            $topThreeStyles = $assessment != null ? Assessment::getTopThreeStyles($assessment) : [];
            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
            $boundary = $assessment != null ? Assessment::getAlchemyPublicName($assessment) : [];
            $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
            $preception = $assessment != null ? Assessment::getPreceptionReport($assessment) : [];
            $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
            $topCommunication = $communication != null ? CodeDetail::getCommunicationPublicName($communication) : [];
            $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : [];

            return view('client-dashboard.dashboard.index', compact('user', 'tip', 'podcast', 'admin_answer', 'topThreeStyles', 'topTwoFeatures', 'boundary', 'topCommunication', 'assessment', 'preception','user_age','energyPool'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function readDailyTip(){

        try {

            DB::beginTransaction();

            $daily_tip_updated = DailyTip::readUserDailyTip();
            $point = 0;
            if (!$daily_tip_updated){

               $point = PointHelper::addPointsOnDailyTipRead();
            }

            DB::commit();

            return Helpers::successResponse('Daily tip read',['point' => $point]);

        }catch (\Exception $exception){

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
