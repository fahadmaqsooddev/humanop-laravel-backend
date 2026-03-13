<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class PanicDetector implements EventDetectorInterface
{

    public function detect(int $userId): void
    {
        $eventType = 'panic';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 10)) {
            return;
        }

        $hrThreshold = (float) config('humanop.thresholds.panic.heart_rate');
        $stepsMax = (float) config('humanop.thresholds.panic.steps_per_minute_max');

        $heartRate = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'heart_rate')
            ->latest('recorded_at')
            ->value('value');

        $steps = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'steps')
            ->latest('recorded_at')
            ->value('value');

        if ($heartRate !== null && $steps !== null && $heartRate > $hrThreshold && $steps < $stepsMax) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.panic'),
                [
                    'heart_rate' => (float) $heartRate,
                    'steps' => (float) $steps,
                ]
            );
        }
    }


}
