<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class ImmaturityDetector implements EventDetectorInterface
{

    public function detect(int $userId): bool
    {
        $eventType = 'immaturity';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 60)) {
            return false;
        }

        $windowHours = (int) config('humanop.thresholds.immaturity.window_hours');
        $stepsMax = (int) config('humanop.thresholds.immaturity.steps_window_max');

        $steps = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'steps')
            ->where('recorded_at', '>=', now()->subHours($windowHours))
            ->sum('value');

        if ($steps < $stepsMax) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.immaturity'),
                [
                    'window_hours' => $windowHours,
                    'steps' => (int) $steps,
                ]
            );

            return true;

        }

        return false;

    }

}
