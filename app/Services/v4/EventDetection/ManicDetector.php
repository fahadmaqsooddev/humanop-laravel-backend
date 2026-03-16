<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Models\v4\Client\DailyMetric;
use App\Services\v4\EventService;

class ManicDetector implements EventDetectorInterface
{

    public function detect(int $userId): bool
    {
        $eventType = 'manic';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 120)) {
            return false;
        }

        $sleepMax = (int) config('humanop.thresholds.manic.sleep_minutes_max');
        $stepsMin = (int) config('humanop.thresholds.manic.steps_next_day_min');

        $dailyMetric = DailyMetric::query()
            ->where('user_id', $userId)
            ->latest('date')
            ->first();

        if (!$dailyMetric || $dailyMetric->sleep_minutes === null) {
            return false;
        }

        $stepsToday = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'steps')
            ->whereDate('recorded_at', today())
            ->sum('value');

        if ($dailyMetric->sleep_minutes < $sleepMax && $stepsToday > $stepsMin) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.manic'),
                [
                    'sleep_minutes' => (int) $dailyMetric->sleep_minutes,
                    'steps_today' => (int) $stepsToday,
                ]
            );

            return true;

        }

        return false;

    }

}
