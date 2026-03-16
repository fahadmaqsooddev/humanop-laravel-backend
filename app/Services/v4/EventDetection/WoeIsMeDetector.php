<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Models\v4\Client\DailyMetric;
use App\Services\v4\EventService;

class WoeIsMeDetector implements EventDetectorInterface
{

    public function detect(int $userId): bool
    {
        $eventType = 'woe_is_me';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 180)) {
            return false;
        }

        $sleepMin = (int) config('humanop.thresholds.woe_is_me.sleep_minutes_min');
        $stepsMax = (int) config('humanop.thresholds.woe_is_me.steps_today_max');

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

        if ($dailyMetric->sleep_minutes > $sleepMin && $stepsToday < $stepsMax) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.woe_is_me'),
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
