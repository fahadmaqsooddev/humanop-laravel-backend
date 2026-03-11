<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class SelfAbsorptionDetector implements EventDetectorInterface
{

    public function detect(int $userId): void
    {
        $eventType = 'self_absorption';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 180)) {
            return;
        }

        $windowDays = (int) config('humanop.thresholds.self_absorption.window_days');
        $stepsMax = (int) config('humanop.thresholds.self_absorption.total_steps_max');

        $steps = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'steps')
            ->where('recorded_at', '>=', now()->subDays($windowDays))
            ->sum('value');

        if ($steps < $stepsMax) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.self_absorption'),
                [
                    'window_days' => $windowDays,
                    'steps' => (int) $steps,
                ]
            );
        }
    }

}
