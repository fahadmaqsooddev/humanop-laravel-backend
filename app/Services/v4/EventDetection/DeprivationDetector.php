<?php

namespace App\Services\v4\EventDetection;

use App\Models\v4\Client\LocationSample;
use App\Services\v4\EventService;

class DeprivationDetector implements EventDetectorInterface
{

    public function detect(int $userId): bool
    {
        $eventType = 'deprivation';

        if (app(EventService::class)->wasRecentlyDetected($userId, $eventType, 180)) {
            return false;
        }

        $windowDays = (int) config('humanop.thresholds.deprivation.window_days');
        $distinctLocationsMin = (int) config('humanop.thresholds.deprivation.distinct_locations_min');

        $locationCount = LocationSample::query()
            ->where('user_id', $userId)
            ->where('recorded_at', '>=', now()->subDays($windowDays))
            ->distinct('place_id')
            ->count('place_id');

        if ($locationCount < $distinctLocationsMin) {
            app(EventService::class)->create(
                $userId,
                $eventType,
                config('humanop.protocols.deprivation'),
                [
                    'window_days' => $windowDays,
                    'distinct_locations' => $locationCount,
                ]
            );

            return true;

        }

        return false;

    }

}
