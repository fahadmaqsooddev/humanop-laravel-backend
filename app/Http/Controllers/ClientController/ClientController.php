<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use App\Models\HAIChai\QueryAnswer;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\TipRecord;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Assessment;
use App\Helpers\Helpers;
class ClientController extends Controller
{

    public function index()
    {
        try {

            $podcast = Podcast::getPodcast();

            $user = Helpers::getWebUser();

            $tip_records = TipRecord::getTipRecord();

            $tip = DailyTip::getSingleTip($tip_records);

            $assessment = Assessment::singleAssessment($user['id']);
            $topThreeStyles = Assessment::getTopThreeStyles($assessment);
            $topFeatures = Assessment::getFeatures($assessment);
            $boundary = Assessment::getAlchemyPublicName($assessment);
            $communication = Assessment::getEnergy($assessment);
            $topTwoFeatures = CodeDetail::getPublicNames($topFeatures['top_two_keys']);
            $topCommunication = CodeDetail::getSinglePublicName($communication[0]);
            $admin_answer = QueryAnswer::userQueryAnswer();

            return view('client-dashboard.dashboard.index', compact('user', 'tip', 'podcast', 'admin_answer','topThreeStyles','topTwoFeatures','boundary','topCommunication','assessment'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
