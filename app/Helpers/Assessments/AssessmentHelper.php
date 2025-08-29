<?php

namespace App\Helpers\Assessments;

use App\Models\Assessment;
use App\Models\CompatibilityReferenceKeys\TraitCompatibilityPolarity;
use App\Models\CompatibilityReferenceKeys\TraitCompatibilityReferenceKeys;
use Illuminate\Support\Facades\Auth;

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


}
