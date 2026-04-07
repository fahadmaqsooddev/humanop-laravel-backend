<?php

namespace App\Services\v4;

use App\Models\v4\Client\BiometricSample;
use App\Models\v4\Client\BoostSession;
use App\Models\v4\Client\DailyMetric;
use App\Models\v4\Client\UserHumanOpProfile;
use App\Support\HumanOpFormula;

class EnergyBoostService
{

    public function startSession(
        int    $userId,
        int    $eventId,
        string $protocolType,
        array  $metadata = []
    ): BoostSession
    {
        $hrBefore = $this->latestMetric($userId, 'heart_rate');
        $hrvBefore = $this->latestMetric($userId, 'hrv_sdnn');

        return BoostSession::query()->create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'protocol_type' => $protocolType,
            'started_at' => now(),
            'hr_before' => $hrBefore,
            'hrv_before' => $hrvBefore,
            'metadata' => $metadata,
        ]);
    }

    public function finalizeSession(BoostSession $session, bool $coherenceAchieved = false): BoostSession
    {
        $session->refresh();

        $durationMinutes = max(1, $session->started_at->diffInMinutes(now()));
        $currentHR = $this->latestMetric($session->user_id, 'heart_rate');
        $currentHRV = $this->latestMetric($session->user_id, 'hrv_sdnn');

        $baselineHRV = DailyMetric::query()
            ->where('user_id', $session->user_id)
            ->latest('date')
            ->value('hrv_baseline');

        $qPhysio = HumanOpFormula::qPhysio(
            (float)($currentHRV ?? 0),
            (float)($baselineHRV ?? 0),
            $coherenceAchieved
        );

        [$traitKey, $traitValue] = $this->resolveTraitModifier($session->user_id, $session->protocol_type);
        [$driverKey, $driverValue] = $this->resolveDriverModifier($session->user_id, $session->protocol_type);

        $ebsPoints = HumanOpFormula::calculateEbs(
            $durationMinutes,
            $qPhysio,
            $driverValue,
            $traitValue
        );

        $capacity = app(EnergyShieldService::class)->getState($session->user_id)->capacity_points;

        $replenishmentPercent = HumanOpFormula::replenishmentPercent($ebsPoints, $capacity);

        $session->ended_at = now();
        $session->hr_after = $currentHR;
        $session->hrv_after = $currentHRV;
        $session->q_physio = $qPhysio;
        $session->energy_points_added = (int)round($ebsPoints);
        $session->replenishment_percent = $replenishmentPercent;
        $session->trait_modifier_key = $traitKey;
        $session->driver_modifier_key = $driverKey;
        $session->trait_modifier_value = $traitValue;
        $session->driver_modifier_value = $driverValue;
        $session->coherence_achieved = $coherenceAchieved;
        $session->save();

        app(EnergyShieldService::class)->addEnergy(
            $session->user_id,
            $ebsPoints
        );

        // Auto-acknowledge the event now that the boost session is complete
        if ($session->event_id) {
            $session->event()->update([
                'acknowledged_at' => now(),
            ]);
        }

        return $session;
    }

    private function latestMetric(int $userId, string $metric): ?float
    {
        $value = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', $metric)
            ->latest('recorded_at')
            ->value('value');

        return $value !== null ? (float)$value : null;
    }

    private function resolveTraitModifier(int $userId, string $protocolType): array
    {
        $profile = UserHumanOpProfile::query()->where('user_id', $userId)->first();

        if (!$profile || !$profile->trait) {
            return ['default', (float)config('humanop.modifiers.trait.default')];
        }

        $trait = strtolower($profile->trait);
        $nowHour = now()->hour;

        if ($protocolType === 'nap' && $trait === 'sympathetic' && $nowHour >= 14 && $nowHour < 17) {
            return ['sympathetic_nap', (float)config('humanop.modifiers.trait.sympathetic_nap')];
        }

        if ($protocolType === 'solitude' && $trait === 'romantic') {
            return ['romantic_solitude', (float)config('humanop.modifiers.trait.romantic_solitude')];
        }

        if ($protocolType === 'movement' && $trait === 'energetic') {
            return ['energetic_movement', (float)config('humanop.modifiers.trait.energetic_movement')];
        }

        return ['default', (float)config('humanop.modifiers.trait.default')];
    }

    private function resolveDriverModifier(int $userId, string $protocolType): array
    {
        $profile = UserHumanOpProfile::query()->where('user_id', $userId)->first();

        if (!$profile || !$profile->pilot_driver) {
            return ['neutral', (float)config('humanop.modifiers.driver.neutral')];
        }

        $pilot = strtolower($profile->pilot_driver);

        $alignedMap = [
            'movement' => ['energetic', 'traveler'],
            'solitude' => ['romantic'],
            'nap' => ['sympathetic'],
            'resonance_breathing' => [],
            'travel' => ['traveler'],
            'social_activity' => ['aesthetic sensibility'],
        ];

        foreach ($alignedMap as $protocol => $driverKeywords) {
            if ($protocolType === $protocol && in_array($pilot, $driverKeywords, true)) {
                return ['aligned', (float)config('humanop.modifiers.driver.aligned')];
            }
        }

        return ['neutral', (float)config('humanop.modifiers.driver.neutral')];
    }

}
