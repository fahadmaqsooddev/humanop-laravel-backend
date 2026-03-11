<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class IntimidationDetector implements EventDetectorInterface
{

    public function detect(int $userId): void
    {
        $eventType = 'intimidation';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 20)) {
            return;
        }

        $hrThreshold = (float) config('humanop.thresholds.intimidation.heart_rate_threshold');
        $hrvLow = (float) config('humanop.thresholds.intimidation.hrv_low_threshold');

        $heartRate = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'heart_rate')
            ->latest('recorded_at')
            ->value('value');

        $hrv = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'hrv_sdnn')
            ->latest('recorded_at')
            ->value('value');

        if ($heartRate !== null && $hrv !== null && $heartRate > $hrThreshold && $hrv < $hrvLow) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.intimidation'),
                [
                    'heart_rate' => (float) $heartRate,
                    'hrv_sdnn' => (float) $hrv,
                ]
            );
        }
    }

}
