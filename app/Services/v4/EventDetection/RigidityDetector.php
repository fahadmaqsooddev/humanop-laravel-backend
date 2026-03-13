<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class RigidityDetector implements EventDetectorInterface
{

    public function detect(int $userId): void
    {
        $eventType = 'rigidity';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 20)) {
            return;
        }

        $hrThreshold = (float) config('humanop.thresholds.rigidity.heart_rate_threshold');

        $scheduleDeviation = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'schedule_deviation')
            ->latest('recorded_at')
            ->value('value');

        $heartRate = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'heart_rate')
            ->latest('recorded_at')
            ->value('value');

        if ($scheduleDeviation !== null && (float) $scheduleDeviation > 0 && $heartRate !== null && $heartRate > $hrThreshold) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.rigidity'),
                [
                    'schedule_deviation' => (float) $scheduleDeviation,
                    'heart_rate' => (float) $heartRate,
                ]
            );
        }
    }

}
