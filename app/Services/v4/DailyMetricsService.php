<?php

namespace App\Services\v4;

use App\Models\v4\Client\BiometricSample;
use App\Models\v4\Client\DailyMetric;
use App\Models\v4\Client\UserHumanOpProfile;
use App\Support\HumanOpFormula;
use Carbon\Carbon;

class DailyMetricsService
{

    public function rebuildForDate(int $userId, Carbon $date): DailyMetric
    {
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $sleepMinutes = $this->calculateSleepMinutes($userId, $start, $end);
        $restingHr = $this->calculateRestingHeartRate($userId, $start, $end);
        $hrvBaseline = $this->calculateThirtyDayHrvBaseline($userId, $date);

        $profile = UserHumanOpProfile::query()->where('user_id', $userId)->first();
        $energyPoolState = $profile?->energy_pool_state ?? 'average';

        $capacity = HumanOpFormula::capacityFromPool($energyPoolState);

        return DailyMetric::query()->updateOrCreate(
            [
                'user_id' => $userId,
                'date' => $date->toDateString(),
            ],
            [
                'hrv_baseline' => $hrvBaseline,
                'resting_hr' => $restingHr,
                'sleep_minutes' => $sleepMinutes,
                'energy_pool_state' => $energyPoolState,
                'capacity_points' => $capacity,
            ]
        );
    }

    public function calculateThirtyDayHrvBaseline(int $userId, Carbon $date): ?float
    {
        $start = $date->copy()->subDays(30)->startOfDay();
        $end = $date->copy()->endOfDay();

        $samples = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'hrv_sdnn')
            ->whereBetween('recorded_at', [$start, $end])
            ->orderBy('value')
            ->pluck('value')
            ->values();

        if ($samples->isEmpty()) {
            return null;
        }

        $count = $samples->count();
        $middle = intdiv($count, 2);

        if ($count % 2 === 0) {
            return round(($samples[$middle - 1] + $samples[$middle]) / 2, 2);
        }

        return round((float) $samples[$middle], 2);
    }

    public function latestBaseline(int $userId): ?float
    {
        return DailyMetric::query()
            ->where('user_id', $userId)
            ->latest('date')
            ->value('hrv_baseline');
    }

    private function calculateSleepMinutes(int $userId, Carbon $start, Carbon $end): ?int
    {
        $sleepStages = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'sleep_stage')
            ->whereBetween('recorded_at', [$start, $end])
            ->pluck('metadata');

        if ($sleepStages->isEmpty()) {
            return null;
        }

        $minutes = 0;

        foreach ($sleepStages as $metadata) {
            if (!is_array($metadata)) {
                continue;
            }

            $segmentStart = isset($metadata['start_at']) ? Carbon::parse($metadata['start_at']) : null;
            $segmentEnd = isset($metadata['end_at']) ? Carbon::parse($metadata['end_at']) : null;

            if ($segmentStart && $segmentEnd && $segmentEnd->greaterThan($segmentStart)) {
                $minutes += $segmentStart->diffInMinutes($segmentEnd);
            }
        }

        return $minutes > 0 ? $minutes : null;
    }

    private function calculateRestingHeartRate(int $userId, Carbon $start, Carbon $end): ?float
    {
        $explicitRhr = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'resting_hr')
            ->whereBetween('recorded_at', [$start, $end])
            ->avg('value');

        if ($explicitRhr !== null) {
            return round((float) $explicitRhr, 2);
        }

        $restHr = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'heart_rate')
            ->whereBetween('recorded_at', [$start, $end])
            ->orderBy('value')
            ->limit(10)
            ->pluck('value');

        if ($restHr->isEmpty()) {
            return null;
        }

        return round((float) $restHr->avg(), 2);
    }

}
