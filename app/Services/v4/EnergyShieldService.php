<?php

namespace App\Services\v4;

use App\Models\v4\Client\EnergyShieldState;
use App\Support\HumanOpFormula;

class EnergyShieldService
{

    public function initializeForPool(int $userId, string $energyPoolState): EnergyShieldState
    {
        $capacity = HumanOpFormula::capacityFromPool($energyPoolState);

        return EnergyShieldState::query()->updateOrCreate(
            ['user_id' => $userId],
            [
                'capacity_points' => $capacity,
                'shield_points' => $capacity,
                'shield_percent' => 100,
                'energy_pool_state' => $energyPoolState,
            ]
        );
    }

    public function syncPoolWithoutReset(int $userId, string $energyPoolState): EnergyShieldState
    {
        $capacity = HumanOpFormula::capacityFromPool($energyPoolState);

        $state = EnergyShieldState::query()->firstOrCreate(
            ['user_id' => $userId],
            [
                'capacity_points' => $capacity,
                'shield_points' => $capacity,
                'shield_percent' => 100,
                'energy_pool_state' => $energyPoolState,
            ]
        );

        $state->capacity_points = $capacity;
        $state->energy_pool_state = $energyPoolState;
        $state->shield_points = min($state->shield_points, $capacity);
        $state->shield_percent = $capacity > 0
            ? round(($state->shield_points / $capacity) * 100, 2)
            : 0.0;
        $state->save();

        return $state;
    }

    /**
     * Client-doc-faithful: add EBS points to shield.
     * No invented drain formula here.
     */
    public function addEnergy(int $userId, float $points): EnergyShieldState
    {
        $state = EnergyShieldState::query()->where('user_id', $userId)->firstOrFail();

        $state->shield_points = (int)min(
            $state->capacity_points,
            round($state->shield_points + $points)
        );

        $state->shield_percent = $state->capacity_points > 0
            ? round(($state->shield_points / $state->capacity_points) * 100, 2)
            : 0.0;

        $state->save();

        return $state;
    }

    public function getState(int $userId): EnergyShieldState
    {
        return EnergyShieldState::query()->where('user_id', $userId)->firstOrFail();
    }

}
