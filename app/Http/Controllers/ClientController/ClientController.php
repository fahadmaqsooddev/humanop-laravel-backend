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
