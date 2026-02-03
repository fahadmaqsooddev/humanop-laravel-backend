<?php

namespace App\Http\Controllers\Api\ClientController\FamilyMatrix;

use App\Helpers\Assessments\AssessmentHelper;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Assessment;
use App\Models\CompatibilityReferenceKeys\DriverCompatibilityReferenceKeys;
use App\Models\CompatibilityReferenceKeys\EnergyPoolCompatibilityReferenceKeys;
use App\Models\FamilyMatrix\FamilyMatrixResponse;
use Illuminate\Http\Request;

class FamilyMatrixController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function familyMatrixAnalyze(Request $request)
    {

        $userId = Helpers::getUser()->id;

        $targetId = $request->input('target_id');

        $getFamilyMatrix = FamilyMatrixResponse::getFamilyMatrix($userId, $targetId);

        if (!empty($getFamilyMatrix)) {

            return Helpers::successResponse('family matrix', $getFamilyMatrix);

        }

        $assessments = $this->getAssessments([$userId, $targetId]);

        if (count($assessments) !== 2) {

            return response()->json(['error' => 'Assessment data incomplete'], 404);

        }

        $userAssessment = $assessments[$userId];

        $targetAssessment = $assessments[$targetId];

        $data = [
            'request_meta' => [
                'relationship_label' => 'Father',
                'user_id' => 'usr_' . $userId,
                'target_id' => 'usr_' . $targetId,
            ],
            'profiles' => [
                'user_self' => $this->buildProfile($userAssessment),
                'target_member' => $this->buildProfile($targetAssessment),
            ],
            'compatibility_matrix' => $this->buildCompatibilityMatrix($userAssessment, $targetAssessment),
        ];

        $response = GuzzleHelpers::sendRequestFromGuzzleForNewHai('post', 'family-matrix/analyze', $data);

        $validationErrors = $this->validateHaiAnalysisResponse($response);

        if (!empty($validationErrors)) {

            $error['validationErrors'] = $validationErrors;

            GuzzleHelpers::sendRequestFromGuzzleForNewHai('post', 'family-matrix/analyze', $error);

        }

        $familyMatrix = FamilyMatrixResponse::createFamilyMatrixResponse($userId, $targetId, $response);

        return Helpers::successResponse('family matrix', $familyMatrix);

    }

    private function validateHaiAnalysisResponse(array $response): array
    {

        $errors = [];

        if (!isset($response['content']) || !is_array($response['content'])) {

            $errors[] = 'Missing content block';

            return $errors;

        }

        $content = $response['content'];

        $requiredTop = ['vibe_check', 'the_physics', 'system_hack'];

        foreach ($requiredTop as $key) {

            if (!isset($content[$key]) || !is_array($content[$key])) {

                $errors[] = "Missing {$key} section";

            }

        }

        if (!empty($errors)) {

            return $errors;

        }

        if (empty($content['vibe_check']['text']) || !is_string($content['vibe_check']['text'])) {

            $errors[] = 'vibe_check.text missing or invalid';

        }

        if (empty($content['the_physics']['friction_analysis']) || !is_string($content['the_physics']['friction_analysis'])) {

            $errors[] = 'the_physics.friction_analysis missing or invalid';

        }

        if (empty($content['the_physics']['flow_analysis']) || !is_string($content['the_physics']['flow_analysis'])) {

            $errors[] = 'the_physics.flow_analysis missing or invalid';

        }

        if (empty($content['system_hack']['title']) || !is_string($content['system_hack']['title'])) {

            $errors[] = 'system_hack.title missing or invalid';

        }

        if (empty($content['system_hack']['actionable_step']) || !is_string($content['system_hack']['actionable_step'])) {

            $errors[] = 'system_hack.actionable_step missing or invalid';

        }

        return $errors;

    }

    private function buildCompatibilityMatrix($userA, $userB): array
    {

        $perception = $this->makeResult(
            AssessmentHelper::polarityCompatabilityCalculate(
                Assessment::getPv($userA),
                Assessment::getPv($userB)
            )
        );

        if ($perception['status'] === 'RED') {

            $perception['flag'] = 'ELECTROMAGNETIC_REPULSION';

        }

        return [
            'traits' => $this->makeResult(
                AssessmentHelper::traitCompatabilityCalculate(
                    Assessment::getTopThreeTraitWeight($userA),
                    Assessment::getTopThreeTraitWeight($userB),
                    $userA,
                    $userB
                )
            ),

            'drivers' => $this->makeResult(
                $this->calculateDriverCompatibility($userA, $userB)
            ),

            'alchemy' => $this->makeResult(
                AssessmentHelper::alchemyCompatabilityCalculate(
                    abs(
                        Assessment::getAlchlCode($userA['id']) -
                        Assessment::getAlchlCode($userB['id'])
                    )
                )
            ),

            'communication_style' => $this->makeResult(
                AssessmentHelper::energyCenterCompatabilityCalculate(
                    Assessment::getEnergyCenter($userA),
                    Assessment::getEnergyCenter($userB)
                )
            ),

            'perception_of_life' => $perception,

            'energy_pool' => $this->makeResult(
                EnergyPoolCompatibilityReferenceKeys::energyPoolCompatabilityCalculate(
                    Assessment::getEnergyPoolDetail($userA)['public_name'],
                    Assessment::getEnergyPoolDetail($userB)['public_name']
                )
            ),
        ];
    }

    private function makeResult($score): array
    {

        $score = round($score, 2);

        return [
            'score' => $score,
            'status' => $this->checkStatus($score),
        ];

    }

    private function calculateDriverCompatibility($userA, $userB): float
    {

        $driversA = Assessment::getFeatures($userA)['top_two_keys'] ?? [];

        $driversB = Assessment::getFeatures($userB)['top_two_keys'] ?? [];

        if (empty($driversA) || empty($driversB)) {

            return 0;

        }

        $scores = [];

        foreach ($driversA as $driverA) {

            foreach ($driversB as $driverB) {

                $scores[] = DriverCompatibilityReferenceKeys::driverCompatabilityCalculate($driverA, $driverB);

                $scores[] = DriverCompatibilityReferenceKeys::driverCompatabilityCalculate($driverB, $driverA);

            }

        }

        return array_sum($scores) / count($scores);

    }

    private function buildProfile($assessment): array
    {

        $features = Assessment::getFeatures($assessment);

        $drivers = Assessment::getTopTwoFeatures($features['top_two_keys'], $assessment);

        $traits = Assessment::topThreeTraits($assessment);

        return [
            'pilot_driver' => $drivers[0]['public_name'] ?? null,
            'co_pilot_driver' => $drivers[1]['public_name'] ?? null,
            'primary_trait' => CodeDetail::getPublicNames($traits)[0][1] ?? null,
            'alchemy' => Assessment::getAlchemyDetail($assessment)['public_name'] ?? null,
            'perception_of_life' => Assessment::getPreceptionReportDetail($assessment)['public_name'] ?? null,
            'energy_center' => CodeDetail::getPublicNames(Assessment::getEnergyCenter($assessment))[0][1] ?? null,
        ];

    }

    private function getAssessments(array $userIds): array
    {

        $data = [];

        foreach ($userIds as $userId) {

            $assessment = Assessment::getLatestAssessment($userId);

            if ($assessment) {

                $data[$userId] = $assessment;

            }

        }

        return $data;

    }

    private function checkStatus($score = null)
    {

        if ($score < 30) {

            return "RED";

        } elseif ($score >= 30 || $score < 70) {

            return "YELLOW";

        } else {

            return "GREEN";

        }
    }

}
