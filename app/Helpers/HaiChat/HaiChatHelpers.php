<?php

namespace App\Helpers\HaiChat;


use App\Enums\Admin\Admin;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Plan\OptimizationPlan;
use App\Models\Assessment;
use App\Models\B2B\SelectIntentionOption;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\IntentionPlan\IntentionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
class HaiChatHelpers
{
    public static function cosineSimilarity($vectorA, $vectorB) {

        $dotProduct = array_sum(array_map(fn($a, $b) => (float)$a * (float)$b, $vectorA, $vectorB));

        $magnitudeA = sqrt(array_sum(array_map(fn($a) => $a ** 2, $vectorA)));

        $magnitudeB = sqrt(array_sum(array_map(fn($b) => $b ** 2, $vectorB)));

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    public static function findRelevantChunks($query, $knowledgeBase, $chunks = 1) {

        $embeddingModel = \OpenAI::client("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");;

        $queryEmbedding = $embeddingModel->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $query,
            ]);

        $queryEmbedding = $queryEmbedding->toArray();

        $queryEmbedding = $queryEmbedding['data'][0] ?? [];

        $results = [];

        foreach ($knowledgeBase as $chunk) {
            $similarity = self::cosineSimilarity($queryEmbedding['embedding'], json_decode($chunk['embedding'], true));
            $results[] = ['content' => $chunk['content'], 'similarity' => $similarity];
        }

        usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

//        $threshold = 0.3;
//
//        $results = array_filter($results, function ($match) use ($threshold) {
//            return $match['similarity'] >= $threshold;
//        });

        return array_slice($results, 0, $chunks);
    }

    public static function findRelevantChunksForGrid($publicNames, $knowledgeBase, $chunks = 1) {

        $embeddingModel = \OpenAI::client("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");;

        $finalResults = [];

        foreach ($publicNames as $key => $publicName){

            $queryEmbedding = $embeddingModel->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => "Detailed overview of " . $key,
            ]);

            $queryEmbedding = $queryEmbedding->toArray();

            $queryEmbedding = $queryEmbedding['data'][0] ?? [];

            $results = [];

            foreach ($knowledgeBase as $chunk) {
                $similarity = self::cosineSimilarity($queryEmbedding['embedding'], json_decode($chunk['embedding'], true));
                $results[] = ['content' => $key .':'.$chunk['content'], 'similarity' => $similarity];
            }

            usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

            $threshold = 0.3;

            $results = array_filter($results, function ($match) use ($threshold) {
                return $match['similarity'] >= $threshold;
            });

            $new_array = array_slice($results, 0, $chunks);

            array_push($finalResults, $new_array);

        }

        return $finalResults;
    }

    public static function syncUserRecordWithHAi($user = null)
    {
        $authUser = Helpers::getUser();

        $user = $user ?? User::user($authUser->id);

        $getAssessment = Assessment::getLatestAssessment($user['id']);

        $coreState = $getAssessment ? Assessment::getCoreState($getAssessment, $user['date_of_birth']) : null;

        $userTrait = $getAssessment ? Assessment::UserTraits($user['id']) : [];

        $styleCodes = Assessment::authenticTraits($getAssessment);

        $userDailyTip = UserDailyTip::where('user_id', $user['id'])->with('dailyTip')->latest()->first();

        $intention = IntentionPlan::getUserIntentionPlan($user['id']);

        $b2bIntentions = SelectIntentionOption::selectB2BIntentionOption($user['id']);

        $userCurrentTraits = User::userDailyTraits($user->id);

        $userPlan = $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB ? 'Premium' : $user['plan_name'];

        $actionPlan = null;

        if (!empty($getAssessment)) {

            $plan = ActionPlan::getActionPlanByAssessmentId($getAssessment, $userPlan);

            if (empty($plan)) {

                $plan = ActionPlan::storeUserActionPlan($getAssessment, $userPlan);

            }

            $planData = OptimizationPlan::getSinglePlan($plan['priority'], $userPlan);

            if ($userPlan === 'Premium') {

                $actionPlan = [
                    'plan_text' => [
                        'intro' => $planData['ninty_days_plan'],
                        'day1_30' => $planData['day1_30'],
                        'day31_60' => $planData['day31_60'],
                        'day61_90' => $planData['day61_90'],
                    ],

                ];
            } else {

                $actionPlan = [
                    'plan_text' => $planData['fourteen_days_plan']
                ];

            }

        }

        $data = [
            'user_detail' => [
                'name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
                'email' => $user['email'] ?? '',
                'phone' => $user['phone'] ?? '',
                'date_of_birth' => $user['date_of_birth'] ?? '',
                'gender' => $user['gender'] ?? '',
                'timezone' => $user['timezone'] ?? '',
                'plan_name' => $user['plan_name'] ?? '',
            ],
            'interval_of_life' => $coreState['interval_of_life'] ?? null,
            'intention_option' => $intention ?? null,
            'all_traits' => $userTrait ?? null,
            'core_state' => $coreState ?? null,
            'authentic_traits' => $userTrait ?? null,
            'top_three_traits' => collect($styleCodes)->pluck('public_name')->toArray() ?? null,
//            'assessment' => $coreState['assessment'] ?? null,
//            'top_two_features' => $coreState['topTwoFeatures'] ?? null,
//            'tertiary_features' => $coreState['tertiaryFeatures'] ?? null,
//            'alchemy' => $coreState['boundary'] ?? null,
//            'energy_center' => $coreState['topCommunication'] ?? null,
//            'energy_pool' => $coreState['energyPool'] ?? null,
//            'perception' => $coreState['perception'] ?? null,
            'optimization_plan' => $actionPlan['plan_text'] ?? null,
            'daily_tip' => $userDailyTip['dailyTip'] ?? null,
            'b2b_intentions' => $b2bIntentions ?? null,
            'current_trait' => $userCurrentTraits ?? null,
        ];

        $body = ["user_id" => $user['id'], "connected_users_data" => $data];

        return GuzzleHelpers::sendRequestFromGuzzleForNewHai('post', "NewHaiApi/users", $body);

    }

    public static function createThreadIds(){


        $response = GuzzleHelpers::sendRequestFromGuzzleForNewHai('get',"NewHaiApi/api/threads-id/recent_users?limit=50&hours=24");

        return $response['result']['users'];

    }
}
