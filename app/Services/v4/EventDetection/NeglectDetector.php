<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class NeglectDetector implements EventDetectorInterface
{

    public function detect(int $userId): bool
    {
        $eventType = 'neglect';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 180)) {
            return false;
        }

        $days = (int) config('humanop.thresholds.neglect.window_days');
        $stepsMax = (int) config('humanop.thresholds.neglect.total_steps_max');

        $from = now()->subDays($days);

        $stepsQuery = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'steps')
            ->where('recorded_at', '>=', $from);

        if (!$stepsQuery->exists()) {
            return false;
        }

        // Ensure data actually spans the full window (not just today's data)
        $earliestSample = (clone $stepsQuery)->min('recorded_at');
        if ($earliestSample && now()->diffInDays($earliestSample) < ($days - 1)) {
            return false;
        }

        $steps = $stepsQuery->sum('value');

        if ($steps < $stepsMax) {

            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.neglect'),
                [
                    'window_days' => $days,
                    'steps' => (int) $steps,
                ]
            );

            return true;
        }

        return false;
    }


}
