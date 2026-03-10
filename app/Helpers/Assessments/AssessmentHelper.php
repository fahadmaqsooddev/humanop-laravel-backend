<?php

namespace App\Helpers\Assessments;

use App\Enums\Admin\Admin;
use App\Models\Assessment;
use App\Models\CompatibilityReferenceKeys\DriverCompatibilityReferenceKeys;
use App\Models\CompatibilityReferenceKeys\EnergyPoolCompatibilityReferenceKeys;
use App\Models\CompatibilityReferenceKeys\TraitCompatibilityPolarity;
use App\Models\CompatibilityReferenceKeys\TraitCompatibilityReferenceKeys;
use App\Models\FamilyMatrix\FamilyMatrixConfiguration;


class AssessmentHelper
{
    public static function getAssessments()
    {

        $assessments = Assessment::getAssessmentIds();

        return $assessments;
    }

    public static function alchemyCompatabilityCalculate($alchemyDifference = null)
    {

        if ($alchemyDifference >= 0 && $alchemyDifference <= 3) {

            return 100;

        } elseif ($alchemyDifference >= 4 && $alchemyDifference <= 7) {

            return 70;

        } elseif ($alchemyDifference >= 8 && $alchemyDifference <= 12) {

            return 30;

        } else {

            return 0;

        }

    }

    public static function polarityCompatabilityCalculate($firstUserPolarity = null, $secondUserPolarity = null)
    {

        $difference = abs($firstUserPolarity - $secondUserPolarity);

        if (($firstUserPolarity >= 3 && $firstUserPolarity <= 10 && $secondUserPolarity >= 3 && $secondUserPolarity <= 10) && ($firstUserPolarity == $secondUserPolarity)) {

            return 100;

        } elseif (($firstUserPolarity >= 3 && $firstUserPolarity <= 10 && $secondUserPolarity >= 3 && $secondUserPolarity <= 10) && ($difference >= 1 && $difference <= 3)) {

            return 97;

        } elseif (($firstUserPolarity >= 3 && $firstUserPolarity <= 10 && $secondUserPolarity >= 3 && $secondUserPolarity <= 10) && ($difference >= 4 && $difference <= 7)) {

            return 94;

        } elseif (($firstUserPolarity >= 10 && $firstUserPolarity <= 13 && $secondUserPolarity >= 10 && $secondUserPolarity <= 13)) {

            return 92;

        } elseif ($firstUserPolarity >= 10 && $firstUserPolarity <= 13 && $secondUserPolarity >= 3 && $secondUserPolarity <= 9) {

            return 89;

        } elseif ($firstUserPolarity >= 14 && $secondUserPolarity >= 10 && $secondUserPolarity <= 13) {

            return 86;

        } elseif ($firstUserPolarity >= 14 && $secondUserPolarity >= 3 && $secondUserPolarity <= 9) {

            return 83;

        } elseif (($firstUserPolarity == 1 || $firstUserPolarity == 2) && ($secondUserPolarity >= 2 && $secondUserPolarity <= 5) && ($firstUserPolarity != $secondUserPolarity)) {

            return 81;

        } elseif (($firstUserPolarity == 1 || $firstUserPolarity == 2) && ($secondUserPolarity >= 5 && $secondUserPolarity <= 9)) {

            return 78;

        } elseif (($firstUserPolarity == 1 || $firstUserPolarity == 2) && ($secondUserPolarity >= 9 && $secondUserPolarity <= 13)) {

            return 75;

        } elseif (($firstUserPolarity == 1 || $firstUserPolarity == 2 || $secondUserPolarity == 1 || $secondUserPolarity == 2) && ($firstUserPolarity == $secondUserPolarity)) {

            return 72;

        } elseif (($firstUserPolarity == 1 || $firstUserPolarity == 2) && ($secondUserPolarity >= 14)) {

            return 70;

        } elseif (($firstUserPolarity == 1 || $firstUserPolarity == 2) && ($secondUserPolarity <= -1 && $secondUserPolarity >= -3)) {

            return 67;

        } elseif (($firstUserPolarity >= -4 && $firstUserPolarity <= -1 && $secondUserPolarity >= -4 && $secondUserPolarity <= -1) && ($firstUserPolarity != $secondUserPolarity)) {

            return 64;

        } elseif (($firstUserPolarity == -1) && ($secondUserPolarity >= 3 && $secondUserPolarity <= 7)) {

            return 61;

        } elseif (($firstUserPolarity == -1) && ($secondUserPolarity >= 8 && $secondUserPolarity <= 10)) {

            return 58;

        } elseif (($firstUserPolarity == -2) && ($secondUserPolarity >= 3 && $secondUserPolarity <= 7)) {

            return 56;

        } elseif (($firstUserPolarity == -2) && ($secondUserPolarity >= 8 && $secondUserPolarity <= 10)) {

            return 53;

        } elseif (($firstUserPolarity == -3) && ($secondUserPolarity >= 3 && $secondUserPolarity <= 7)) {

            return 50;

        } elseif (($firstUserPolarity == -3) && ($secondUserPolarity >= 8 && $secondUserPolarity <= 10)) {

            return 47;

        } elseif ($firstUserPolarity >= 14 && $secondUserPolarity >= 14) {

            return 44;

        } elseif (($firstUserPolarity == -1) && ($secondUserPolarity >= 11)) {

            return 42;

        } elseif (($firstUserPolarity == -2) && ($secondUserPolarity >= 11)) {

            return 39;

        } elseif (($firstUserPolarity == -3) && ($secondUserPolarity >= 11)) {

            return 36;

        } elseif ($firstUserPolarity == -1 && $secondUserPolarity == -1) {

            return 33;

        } elseif ($firstUserPolarity == -2 && $secondUserPolarity == -2) {

            return 31;

        } elseif ($firstUserPolarity == -3 && $secondUserPolarity == -3) {

            return 28;

        } elseif ($firstUserPolarity == -4 && $secondUserPolarity == -4) {

            return 25;

        } elseif (($firstUserPolarity < -4) && ($secondUserPolarity >= 1 && $secondUserPolarity <= 4)) {

            return 22;

        } elseif (($firstUserPolarity < -4) && ($secondUserPolarity >= 5)) {

            return 19;

        } elseif (($firstUserPolarity == 0) && ($secondUserPolarity >= 1 && $secondUserPolarity <= 7)) {

            return 17;

        } elseif (($firstUserPolarity == 0) && ($secondUserPolarity >= 8 && $secondUserPolarity <= 10)) {

            return 14;

        } elseif (($firstUserPolarity == 0) && ($secondUserPolarity >= 11 && $secondUserPolarity <= 13)) {

            return 11;

        } elseif ($firstUserPolarity == 0 && $secondUserPolarity >= 14) {

            return 8;

        } elseif (($firstUserPolarity == 0) && ($secondUserPolarity <= -1 && $secondUserPolarity >= -3)) {

            return 6;

        } elseif (($firstUserPolarity == 0) && ($secondUserPolarity <= -4)) {

            return 3;

        } elseif (($firstUserPolarity == 0) && ($secondUserPolarity == 0)) {

            return 0;

        } else {

            return 0;

        }

    }

    public static function energyCenterCompatabilityCalculate($firstUserEnergyCenter = null, $secondUserEnergyCenter = null)
    {
        $grid1 = array_keys($firstUserEnergyCenter);

        $grid2 = array_keys($secondUserEnergyCenter);

        if ($grid1 === $grid2) {

            return 100;

        } elseif (array_slice($grid1, 0, 2) === array_slice($grid2, 0, 2)) {

            return 88;

        } elseif (array_slice($grid1, 0, 1) === array_slice($grid2, 0, 1)) {

            return 76;

        } elseif ((array_values(array_slice($firstUserEnergyCenter, 0, 2)) == array_values(array_slice($secondUserEnergyCenter, 0, 2))) && (array_keys(array_slice($firstUserEnergyCenter, 0, 2)) != array_keys(array_slice($secondUserEnergyCenter, 0, 2)))) {

            return 64;

        } elseif (((array_values(array_slice($firstUserEnergyCenter, 0, 1)) == array_values(array_slice($secondUserEnergyCenter, 0, 1))) || (array_values(array_slice($firstUserEnergyCenter, 1, 1)) == array_values(array_slice($secondUserEnergyCenter, 1, 1)))) && (array_keys(array_slice($firstUserEnergyCenter, 0, 2)) != array_keys(array_slice($secondUserEnergyCenter, 0, 2)))) {

            return 52;

        } elseif (array_slice($grid1, 1, 1) === array_slice($grid2, 1, 1)) {

            return 40;

        } elseif (array_slice($grid1, 2, 1) === array_slice($grid2, 2, 1)) {

            return 28;

        } elseif ((array_values(array_slice($firstUserEnergyCenter, 0, 2)) == array_values(array_slice($firstUserEnergyCenter, 2, 2))) && (array_values(array_slice($secondUserEnergyCenter, 0, 2)) == array_values(array_slice($secondUserEnergyCenter, 2, 2)))) {

            return 16;

        } elseif (array_keys(array_slice($firstUserEnergyCenter, 0, 4)) == array_reverse(array_keys(array_slice($secondUserEnergyCenter, 0, 4)))) {

            return 0;

        } else {

            return 0;

        }

    }

    public static function traitCompatabilityCalculate($firstUserTrait = null, $secondUserTrait = null, $firstAssessment = null, $secondAssessment = null)
    {

        $deviation = [
            'first_deviation' => abs($firstUserTrait['first_weight'] - $secondUserTrait['first_weight']),
            'second_deviation' => abs($firstUserTrait['second_weight'] - $secondUserTrait['second_weight']),
            'third_deviation' => abs($firstUserTrait['third_weight'] - $secondUserTrait['third_weight']),
        ];

        $firstUserTopThreeTraits = Assessment::topThreeTraits($firstAssessment);

        $secondUserTopThreeTraits = Assessment::topThreeTraits($secondAssessment);

        $traitPolarities = TraitCompatibilityPolarity::getPolarity($firstUserTopThreeTraits, $secondUserTopThreeTraits);

        $traitScore = TraitCompatibilityReferenceKeys::getCompatabilityScore($firstUserTopThreeTraits, $secondUserTopThreeTraits);

        $calculateDeviation = [
            'first' => $traitScore['first_score'] + ($traitScore['first_score'] * ($deviation['first_deviation'] / 100) * $traitPolarities[0]),
            'second' => $traitScore['second_score'] + ($traitScore['second_score'] * ($deviation['second_deviation'] / 100) * $traitPolarities[1]),
            'third' => $traitScore['third_score'] + ($traitScore['third_score'] * ($deviation['third_deviation'] / 100) * $traitPolarities[2]),
        ];

        $adjustedCompatabilityScore = [
            'first' => ($calculateDeviation['first'] + $traitScore['first_score']) / 2,
            'second' => ($calculateDeviation['second'] + $traitScore['second_score']) / 2,
            'third' => ($calculateDeviation['third'] + $traitScore['third_score']) / 2,
        ];

        $values = array_values($adjustedCompatabilityScore);

        sort($values);

        $mean_compatabilityScore = array_sum($adjustedCompatabilityScore) / 3;

        $median_compatabilityScore = $values[1];

        $finalCompatabilityScore = max($mean_compatabilityScore, $median_compatabilityScore);

        return round($finalCompatabilityScore, 2);
    }

    public static function gridResultsCompatabilityCalculate($traitCompatability = null, $driverCompatability = null, $alchemyCompatability = null, $energyCenterCompatability = null, $polarityCompatability = null, $energyPoolCompatability = null)
    {

        $traitScore = ($traitCompatability / 100) * 20;
        $driverScore = ($driverCompatability / 100) * 20;
        $alchemyScore = ($alchemyCompatability / 100) * 25;
        $energyCenterScore = ($energyCenterCompatability / 100) * 15;
        $polarityScore = ($polarityCompatability / 100) * 15;
        $energyPoolScore = ($energyPoolCompatability / 100) * 5;

        $gridDataResult = $traitScore + $driverScore + $alchemyScore + $energyPoolScore + $polarityScore + $energyCenterScore;

        return round($gridDataResult, 2);

    }

    public static function getCoreStatsData($assessment = null, $user = null,$assessmentPermission=null)
    {


        $coreState = Assessment::getCoreState($assessment, $user->date_of_birth);

        $traits = [];

        if ($assessmentPermission->traits === Admin::IS_DISPLAY) {

            foreach ($coreState['topThreeStyles'] as $style) {

                $traits[] = [
                    'public_name' => $style['public_name'],
                    'code_number' => $style['code_number'],
                ];

            }
        }

        $features = [];
 

        if ($assessmentPermission->motivational_driver === Admin::IS_DISPLAY) {


            foreach ($coreState['topTwoFeatures'] as $style) {

                $features[] = [
                    'public_name' => $style['public_name'],
                    'code_number' => $style['code_number'],
                ];

            }
        }

        $communications = [];

        if ($assessmentPermission->communication_style == Admin::IS_DISPLAY) {
            foreach ($coreState['topCommunication'] as $style) {
                $communications[] = [
                    'public_name' => $style['public_name'],
                    'code_number' => $style['code_number'],
                ];
            }
        }

        $boundary = null;
        if ($assessmentPermission->alchemic_boundaries === Admin::IS_DISPLAY) {
              $boundary = [
                'public_name' => $coreState['boundary']['public_name'],
                'code_number' => $coreState['boundary']['code_number'],
            ];
        }
      
        $energyPool = null;
        $explode = explode('[', $coreState['energyPool']['public_name']);

        if ($assessmentPermission->energy_pool === Admin::IS_DISPLAY) {
            $energyPool = [
                'public_name' => trim($explode[0]),
                'code_number' => isset($explode[1]) ? rtrim($explode[1], ']') : null,
            ];
        }

        $perception = null;
        if ($assessmentPermission->perception_of_life === Admin::IS_DISPLAY) {
             $perception = [
                'public_name' => $coreState['perception']['public_name'],
                'code_number' => $coreState['perception']['pv'],
            ];
        }


        $intervalOfLife = null;
        if ($assessmentPermission->interval_of_life === Admin::IS_DISPLAY) {
            $intervalOfLife = $coreState['interval_of_life']['public_name'];
        }


        $authenticTraits = null;
        if ($assessmentPermission->authentic_traits === Admin::IS_DISPLAY) {
            $authenticTraits = $coreState['authentic_traits'] ?? null;
        }

       
        $coreStateField = null;
        if ($assessmentPermission->core_state === Admin::IS_DISPLAY) {
            $coreStateField = $coreState['core_state'] ?? null;
        }

    
        return array_filter([
            'interval_of_life' => $intervalOfLife,
            'traits' => $traits,
            'features' => $features,
            'communications' => $communications,
            'boundary' => $boundary,
            'energy_pool' => $energyPool,
            'perception' => $perception,
            'authentic_traits' => $authenticTraits,
            'core_state' => $coreStateField,
        ]);
    }

    public static function getUserAssessments(array $userIds): array
    {
        return Assessment::whereIn('user_id', $userIds)

            ->where('page', 0)

            ->orderByDesc('created_at')

            ->get()

            ->unique('user_id')

            ->keyBy('user_id')

            ->toArray();

    }

    public static function buildCompatibilityMatrix($userA, $userB): array
    {

        $perception = self::makeResult(
            AssessmentHelper::polarityCompatabilityCalculate(
                Assessment::getPv($userA),
                Assessment::getPv($userB)
            ), 'Perception Of Life'
        );

        if ($perception['status'] === Admin::RED) {

            $perception['flag'] = Admin::ELECTROMAGNETIC_REPULSION;

        }

        return [
            'cms_score' => $perception['score'],
            'traits' => self::makeResult(
                AssessmentHelper::traitCompatabilityCalculate(
                    Assessment::getTopThreeTraitWeight($userA),
                    Assessment::getTopThreeTraitWeight($userB),
                    $userA,
                    $userB
                ), 'Traits'
            ),

            'drivers' => self::makeResult(
                self::calculateDriverCompatibility($userA, $userB), 'Motivational Driver (Pilot)'
            ),

            'alchemy' => self::makeResult(
                AssessmentHelper::alchemyCompatabilityCalculate(
                    abs(
                        Assessment::getAlchlCode($userA['id']) -
                        Assessment::getAlchlCode($userB['id'])
                    )
                ), 'Alchemy'
            ),

            'communication_style' => self::makeResult(
                AssessmentHelper::energyCenterCompatabilityCalculate(
                    Assessment::getEnergyCenter($userA),
                    Assessment::getEnergyCenter($userB)
                ), 'Communication Styles'
            ),

            'perception_of_life' => $perception,

            'energy_pool' => self::makeResult(
                EnergyPoolCompatibilityReferenceKeys::energyPoolCompatabilityCalculate(
                    Assessment::getEnergyPoolDetail($userA)['public_name'],
                    Assessment::getEnergyPoolDetail($userB)['public_name']
                ), 'Energy Pool'
            ),
        ];
    }

    public static function makeResult($score = null, $grid = null): array
    {

        $score = round($score, 2);

        $colorCode = self::checkStatus($score);

        $familyMatrixConfigration = FamilyMatrixConfiguration::where('grid_name', $grid)->where('color_code', $colorCode)->first();

        return [
            'score' => $score,
            'status' => $colorCode,
            'text' => $familyMatrixConfigration ? $familyMatrixConfigration->text : '',
        ];

    }

    public static function checkStatus($score = null)
    {

        if ($score < 30) {

            return Admin::RED;

        } elseif ($score >= 30 && $score < 70) {

            return Admin::YELLOW;

        } else {

            return Admin::GREEN;

        }
    }

    public static function calculateDriverCompatibility($userA, $userB): float
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


}
