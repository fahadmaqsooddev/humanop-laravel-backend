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

            $admin_answer = QueryAnswer::userQueryAnswer();

            $assessment = Assessment::getLatestAssessment($user['id']);
            $topThreeStyles = $assessment != null ? Assessment::getTopThreeStyles($assessment) : [];
            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
            $boundary = $assessment != null ? Assessment::getAlchemyPublicName($assessment) : [];
            $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
            $preception = $assessment != null ? Assessment::getPreceptionReport($assessment) : [];
            $topTwoFeatures = $topFeatures != null ? CodeDetail::getPublicNames($topFeatures['top_two_keys']) : [];
            $topCommunication = $communication != null ? CodeDetail::getSinglePublicName($communication[0]) : [];

            return view('client-dashboard.dashboard.index', compact('user', 'tip', 'podcast', 'admin_answer', 'topThreeStyles', 'topTwoFeatures', 'boundary', 'topCommunication', 'assessment', 'preception'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
