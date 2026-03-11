<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class VolatilityDetector implements EventDetectorInterface
{

    public function detect(int $userId): void
    {
        $eventType = 'volatility';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 15)) {
            return;
        }

        $windowMinutes = (int) config('humanop.thresholds.volatility.window_minutes');
        $deltaThreshold = (float) config('humanop.thresholds.volatility.heart_rate_range_delta');
        $minSamples = (int) config('humanop.thresholds.volatility.minimum_samples');

        $samples = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'heart_rate')
            ->where('recorded_at', '>=', now()->subMinutes($windowMinutes))
            ->pluck('value');

        if ($samples->count() < $minSamples) {
            return;
        }

        $range = (float) $samples->max() - (float) $samples->min();

        if ($range > $deltaThreshold) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.volatility'),
                [
                    'window_minutes' => $windowMinutes,
                    'heart_rate_range' => $range,
                    'min_hr' => (float) $samples->min(),
                    'max_hr' => (float) $samples->max(),
                ]
            );
        }
    }

}
