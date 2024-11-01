<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Points\PointHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Client\Point\PointLog;
use App\Models\HAIChai\QueryAnswer;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Information\InformationIcon;
use App\Models\TipRecord;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Assessment;
use App\Helpers\Helpers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{

    public function index()
    {
        try {
            $user_age = User::getUserAge(Helpers::getWebUser()->date_of_birth);
            $age = Carbon::parse(Helpers::getWebUser()->date_of_birth)->age;
            $podcast = Podcast::getPodcast();
            $user = Helpers::getWebUser();
            $userPlanName = $user['plan_name'];
            $tip = DailyTip::getTodayTip();
            $plan = ActionPlan::userActionPlan();
            $admin_answer = QueryAnswer::userQueryAnswer();
            $assessment = Assessment::getLatestAssessment($user['id']);
            $topThreeStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];
            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
            $boundary = $assessment != null ? Assessment::getAlchemyPublicName($assessment) : [];
            $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
            $preception = $assessment != null ? Assessment::getPreceptionReport($assessment) : [];
            $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
            $topCommunication = $communication != null ? CodeDetail::getCommunicationPublicName($communication) : [];
            $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : [];

            $coreStatsInfo = InformationIcon::getCoreStatsInfo();
            $actionPlanInfo = InformationIcon::getActionPlanInfo();
            $dailyTipInfo = InformationIcon::getDailyTipInfo();

            $libraryResourceInfo = InformationIcon::getLibraryResourceInfo();
            $helpInfo = InformationIcon::getHelpInfo();

            return view('client-dashboard.dashboard.index', compact('user', 'tip', 'podcast', 'admin_answer', 'topThreeStyles', 'topTwoFeatures', 'boundary', 'topCommunication', 'assessment', 'preception', 'user_age', 'energyPool', 'plan', 'userPlanName', 'age', 'coreStatsInfo', 'helpInfo', 'actionPlanInfo', 'dailyTipInfo', 'libraryResourceInfo'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }


    public function ninetyDayPlan()
    {
        $assessmentDetails = Assessment::getAllRowGrid(841); // 860
        $user = Helpers::getWebUser();

        $plan = $user['plan_name'];

        $body = ['grid' => $assessmentDetails, 'plan' => $plan];

        $data = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/90day_plan', $body);

        return $data;
    }

    public function readDailyTip()
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


    public function completeIntro()
    {

        try {


              User::updateUser(['intro_check' => 1],Helpers::getWebUser()['id']);


            return Helpers::successResponse('Intro Completed Successfully');

        } catch (\Exception $exception) {


            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
