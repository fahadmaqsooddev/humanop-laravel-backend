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

        $windowDays = (int) config('humanop.thresholds.neglect.window_days');
        $stepsMax = (int) config('humanop.thresholds.neglect.total_steps_max');

        $steps = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'steps')
            ->where('recorded_at', '>=', now()->subDays($windowDays))
            ->sum('value');

        if ($steps < $stepsMax) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.neglect'),
                [
                    'window_days' => $windowDays,
                    'steps' => (int) $steps,
                ]
            );

            return true;
        }
        return false;
    }


}
