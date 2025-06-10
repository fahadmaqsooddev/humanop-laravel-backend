<?php

namespace App\Helpers\HaiChat;


use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Assessment;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\IntentionPlan\IntentionPlan;
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

    public static function syncUserRecordWithHAi($user){

        $user = ($user ?? Helpers::getUser());

        $getAssessment = Assessment::getLatestAssessment($user['id']);

        $optimizationPlan = $getAssessment ? ActionPlan::getUserActionPlan($user['id']) : null;

        $coreState = $getAssessment ? Assessment::getCoreState($getAssessment, $user['date_of_birth']) : null;

        $userTrait = $getAssessment ? Assessment::UserTraits($user['id']) : [];

        $userDailyTip = UserDailyTip::where('user_id', $user['id'])->with('dailyTip')->latest()->first();

        $intention = IntentionPlan::getUserIntentionPlan($user['id']);

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
            'interval_of_life' => $coreState['interval_of_life'],
            'intention_option' => $intention,
            'assessment' => $coreState['assessment'],
            'all_traits' => $userTrait,
            'top_three_traits' => $coreState['topThreeStyles'],
            'top_two_features' => $coreState['topTwoFeatures'],
            'tertiary_features' => $coreState['tertiaryFeatures'],
            'alchemy' => $coreState['boundary'],
            'energy_center' => $coreState['topCommunication'],
            'energy_pool' => $coreState['energyPool'],
            'perception' => $coreState['perception'],
            'optimization_plan' => $optimizationPlan,
            'daily_tip' => $userDailyTip['dailyTip'],

        ];

        $body = ["user_id" => $user['id'], "connected_users_data" => $data];

        GuzzleHelpers::sendRequestFromGuzzleForNewHai('post',"NewHaiApi/users", $body);

    }
}
