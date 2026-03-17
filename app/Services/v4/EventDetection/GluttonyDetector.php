<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\BiometricSample;
use App\Services\v4\EventService;

class GluttonyDetector implements EventDetectorInterface
{

    public function detect(int $userId): bool
    {
        $eventType = 'gluttony';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 60)) {
            return false;
        }

        $eveningStart = (int) config('humanop.thresholds.gluttony.evening_hour_start');
        $hrvLow = (float) config('humanop.thresholds.gluttony.hrv_low_threshold');
        $minSamples = (int) config('humanop.thresholds.gluttony.min_hrv_samples');

        if (now()->hour < $eveningStart) {
            return false;
        }

        $query = BiometricSample::query()
            ->where('user_id', $userId)
            ->where('metric', 'hrv_sdnn')
            ->whereDate('recorded_at', today());

        // Require sufficient data
        if ($query->count() < $minSamples) {
            return false;
        }

        $avgHrvToday = $query->avg('value');

        if ($avgHrvToday !== null && $avgHrvToday < $hrvLow) {

            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.gluttony'),
                [
                    'avg_hrv_today' => round((float) $avgHrvToday, 2),
                    'samples' => $query->count(),
                    'hour' => now()->hour,
                ]
            );

            return true;
        }

        return false;
    }

}
