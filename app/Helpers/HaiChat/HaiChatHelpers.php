<?php

namespace App\Helpers\HaiChat;


use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Assessment;
use App\Models\B2B\SelectIntentionOption;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\IntentionPlan\IntentionPlan;
class HaiChatHelpers
{
    public static function syncUserRecordWithHAi($user = null){

        $user = ($user ?? Helpers::getUser());

        $getAssessment = Assessment::getLatestAssessment($user['id']);

        $optimizationPlan = $getAssessment ? ActionPlan::getUserActionPlan($user['id']) : null;

        $coreState = $getAssessment ? Assessment::getCoreState($getAssessment, $user['date_of_birth']) : null;

        $userTrait = $getAssessment ? Assessment::UserTraits($user['id']) : [];

        $userDailyTip = UserDailyTip::where('user_id', $user['id'])->with('dailyTip')->latest()->first();

        $intention = IntentionPlan::getUserIntentionPlan($user['id']);

        $b2b_intentions = SelectIntentionOption::selectB2BIntentionOption($user['id']);

        $data = [
            'user_detail' => [
                'name' => ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''),
                'email' => $user['email'] ?? '',
                'phone' => $user['phone'] ?? '',
                'date_of_birth' => $user['date_of_birth'] ?? '',
                'gender' => $user['gender'] ?? '',
                'timezone' => $user['timezone'] ?? '',
                'plan_name' => $user['plan_name'] ?? ''
            ],
            'interval_of_life' => ($coreState['interval_of_life'] ?? null),
            'intention_option' => $intention,
            'assessment' => ($coreState['assessment'] ?? null),
            'all_traits' => $userTrait,
            'top_three_traits' => ($coreState['topThreeStyles'] ?? null),
            'top_two_features' => ($coreState['topTwoFeatures'] ?? null),
            'tertiary_features' => ($coreState['tertiaryFeatures'] ?? null),
            'alchemy' => ($coreState['boundary'] ?? null),
            'energy_center' => ($coreState['topCommunication'] ?? null),
            'energy_pool' => ($coreState['energyPool'] ?? null),
            'perception' => ($coreState['perception'] ?? null),
            'optimization_plan' => $optimizationPlan,
            'daily_tip' => ($userDailyTip['dailyTip'] ?? null),
            'b2b_intentions' => $b2b_intentions,

        ];

        $body = ["user_id" => $user['id'], "connected_users_data" => $data];

        GuzzleHelpers::sendRequestFromGuzzleForNewHai('post',"NewHaiApi/users", $body);

    }
}
