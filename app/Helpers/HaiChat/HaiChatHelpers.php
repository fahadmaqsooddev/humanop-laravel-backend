<?php

namespace App\Helpers\HaiChat;


use App\Enums\Admin\Admin;
use App\Helpers\Assessments\AssessmentHelper;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Plan\OptimizationPlan;
use App\Models\Assessment;
use App\Models\B2B\SelectIntentionOption;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\FamilyMatrix\AssignFamilyMatrixRelationship;
use App\Models\FamilyMatrix\FamilyMatrixRelationship;
use App\Models\FamilyMatrix\FamilyMatrixResponse;
use App\Models\IntentionPlan\IntentionPlan;
use App\Models\User;
use Carbon\Carbon;
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

        $embeddingModel = \OpenAI::client(config('openAi.credentials.api'));

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

        $embeddingModel = \OpenAI::client(config('openAi.credentials.api'));

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

        if ($user === null) {

            $authUser = Helpers::getUser();

            $user = User::user($authUser->id);

        }

        $userId = $user['id'] ?? $user->id ?? null;

        if (!$userId) {

            return null;

        }

        $userAssessment = Assessment::getLatestAssessment($userId);

        $userPlan = in_array($user['plan_name'], ['Freemium', 'Premium']) ? $user['plan_name'] : 'Beta Breaker Club';

        $coreState = null;
        $userTrait = [];
        $styleCodes = [];
        $actionPlan = null;

        if (!empty($userAssessment)) {

            $coreState = Assessment::getCoreState($userAssessment, $user['date_of_birth'] ?? $user->date_of_birth ?? null);

            $userTrait = Assessment::UserTraits($userId);

            $plan = ActionPlan::getActionPlanByAssessmentId($userAssessment, $userPlan);

            if (empty($plan)) {

                $plan = ActionPlan::storeUserActionPlan($userAssessment, $userPlan);

            }

            if (!empty($plan)) {

                $planData = OptimizationPlan::getSinglePlan($plan['priority'], $userPlan);

                if ($userPlan === 'Premium') {

                    $actionPlan = [
                        'plan_text' => [
                            'intro' => $planData['ninty_days_plan'] ?? null,
                            'day1_30' => $planData['day1_30'] ?? null,
                            'day31_60' => $planData['day31_60'] ?? null,
                            'day61_90' => $planData['day61_90'] ?? null,
                        ],

                    ];

                } else {

                    $actionPlan = [
                        'plan_text' => $planData['fourteen_days_plan'] ?? null
                    ];
                }
            }
        }

        $userDailyTip = UserDailyTip::where('user_id', $userId)->with('dailyTip')->latest()->first();

        $intention = IntentionPlan::getUserIntentionPlan($userId);

        $b2bIntentions = SelectIntentionOption::selectB2BIntentionOption($userId);

        $userCurrentTraits = User::userDailyTraits($userId);

        $firstName = $user['first_name'] ?? $user->first_name ?? '';

        $lastName = $user['last_name'] ?? $user->last_name ?? '';

        $rawGender = $user['gender'] ?? $user->gender ?? null;
        $gender = is_numeric($rawGender)
            ? ((int) $rawGender === Admin::IS_MALE ? 'Male' : 'Female')
            : ($rawGender ?: '');

        $rawLastLogin = $user['last_login'] ?? $user->last_login ?? null;
        $lastLogin = null;
        if (!empty($rawLastLogin)) {
            try {
                $lastLogin = Carbon::parse($rawLastLogin)->format('m/d/Y h:i A');
            } catch (\Throwable $e) {
                $lastLogin = (string) $rawLastLogin;
            }
        }

        $familyRelations = AssignFamilyMatrixRelationship::with('relationship')->where('user_id', $userId)->get();

        $familyConnections = [];

        $targetIds = $familyRelations->pluck('target_id')->filter()->unique()->values()->all();

        $targetUsersById = collect();
        if (!empty($targetIds)) {
            $targetUsersById = User::whereIn('id', $targetIds)->get()->keyBy('id');
        }

        $matrixResponsesByTargetId = collect();
        if (!empty($targetIds)) {
            $matrixResponsesByTargetId = FamilyMatrixResponse::where('user_id', $userId)
                ->whereIn('target_id', $targetIds)
                ->get()
                ->keyBy('target_id');
        }

        foreach ($familyRelations as $familyRelation) {

            $familyUser = $targetUsersById->get($familyRelation->target_id);

            $compatibilityMatrix = $familyUser
                ? Helpers::compatibilityMatchingBetweenTwoUsers($user, $familyUser)
                : [
                    'first_user_traits' => [],
                    'second_user_traits' => [],
                    'compatibility_score' => null,
                ];

            $getRelation = $familyRelation->relationship ? $familyRelation->relationship->relationship_name : null;

            $familyMatrixResponse = $matrixResponsesByTargetId->get($familyRelation->target_id);

            $familyConnections[] = [
                'user_id' => $userId,
                'target_id' => $familyRelation->target_id ?? null,
                'relation' => $getRelation ?? null,
                'score' => $compatibilityMatrix['compatibility_score'] ?? null,
                'hai_response' => [
                    'vide_check_text' => $familyMatrixResponse?->vide_check_text ?? null,
                    'physics_friction_analysis' => $familyMatrixResponse?->physics_friction_analysis ?? null,
                    'physics_flow_analysis' => $familyMatrixResponse?->physics_flow_analysis ?? null,
                    'system_hack_title' => $familyMatrixResponse?->system_hack_title ?? null,
                    'system_hack_actionable_step' => $familyMatrixResponse?->system_hack_actionable_step ?? null,
                ]
            ];
        }

        $data = [
            'user_detail' => [
                'name' => trim("{$firstName} {$lastName}"),
                'email' => $user['email'] ?? $user->email ?? '',
                'phone' => $user['phone'] ?? $user->phone ?? '',
                'date_of_birth' => $user['date_of_birth'] ?? $user->date_of_birth ?? '',
                'gender' => $gender,
                'last_login' => $lastLogin,
                'timezone' => $user['timezone'] ?? $user->timezone ?? '',
                'plan_name' => $user['plan_name'] ?? $user->plan_name ?? '',
            ],
            'family_connection' => $familyConnections,
            'interval_of_life' => $coreState['interval_of_life'] ?? null,
            'intention_option' => $intention,
            'all_traits' => $userTrait ?: null,
            'assessment' => $coreState['assessment'] ?? null,
            'top_three_traits' => $coreState['topThreeStyles'] ?? null,
            'authentic_traits' => $userTrait ?: null,
            'top_two_features' => $coreState['topTwoFeatures'] ?? null,
            'tertiary_features' => $coreState['tertiaryFeatures'] ?? null,
            'alchemy' => $coreState['boundary'] ?? null,
            'energy_center' => $coreState['topCommunication'] ?? null,
            'energy_pool' => $coreState['energyPool'] ?? null,
            'perception' => $coreState['perception'] ?? null,
            'optimization_plan' => $actionPlan['plan_text'] ?? null,
            'daily_tip' => $userDailyTip->dailyTip ?? $userDailyTip['dailyTip'] ?? null,
            'b2b_intentions' => $b2bIntentions,
            'current_trait' => $userCurrentTraits,
        ];

        $body = [
            "user_id" => $userId,
            "connected_users_data" => $data
        ];

        return GuzzleHelpers::sendRequestFromGuzzleForNewHai('post', "NewHaiApi/users", $body);
    }

    public static function createThreadIds(){


        $response = GuzzleHelpers::sendRequestFromGuzzleForNewHai('get',"NewHaiApi/api/threads-id/recent_users?limit=50&hours=24");

        return $response['result']['users'];

    }
}
