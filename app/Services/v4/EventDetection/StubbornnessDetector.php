<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class StubbornnessDetector implements EventDetectorInterface
{

    public function detect(int $userId): bool
    {
        $eventType = 'stubbornness';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 30)) {
            return false;
        }

        $minutes = (int) config('humanop.thresholds.stubbornness.minutes_low_hrv_sedentary_min');
        $stepsMax = (float) config('humanop.thresholds.stubbornness.sedentary_total_steps_max');
        $hrvLow = (float) config('humanop.thresholds.stubbornness.hrv_low_threshold');

        $from = now()->subMinutes($minutes);

        $avgHrv = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'hrv_sdnn')
            ->where('recorded_at', '>=', $from)
            ->avg('value');

        $steps = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'steps')
            ->where('recorded_at', '>=', $from)
            ->sum('value');

        if ($avgHrv !== null && $avgHrv < $hrvLow && $steps < $stepsMax) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.stubbornness'),
                [
                    'window_minutes' => $minutes,
                    'avg_hrv' => round((float) $avgHrv, 2),
                    'steps' => (float) $steps,
                ]
            );

            return true;
        }
        return false;
    }

}
