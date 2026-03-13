<?php

namespace App\Support;

class HumanOpFormula
{
    public static function capacityFromPool(string $pool): int
    {
        return (int)config("humanop.energy_capacity.{$pool}", 500);
    }

    /**
     * EBS = (T_boost × Q_physio) × M_driver × M_trait
     */
    public static function calculateEbs(
        float $tBoost,
        float $qPhysio,
        float $mDriver,
        float $mTrait
    ): float
    {
        return ($tBoost * $qPhysio) * $mDriver * $mTrait;
    }

    /**
     * replenishment % = EBS / capacity_points * 100
     */
    public static function replenishmentPercent(float $ebsPoints, int $capacityPoints): float
    {
        if ($capacityPoints <= 0) {
            return 0.0;
        }

        return round(($ebsPoints / $capacityPoints) * 100, 2);
    }

    /**
     * High fidelity mode from client docs:
     * Q_physio = (current_HRV / baseline_HRV) × coherence_factor
     */
    public static function qPhysio(float $currentHRV, float $baselineHRV, bool $coherent): float
    {
        if ($baselineHRV <= 0) {
            return 0.0;
        }

        $coherenceFactor = $coherent
            ? (float)config('humanop.q_physio.coherence_factor.coherent')
            : (float)config('humanop.q_physio.coherence_factor.normal');

        return round(($currentHRV / $baselineHRV) * $coherenceFactor, 4);
    }
}
