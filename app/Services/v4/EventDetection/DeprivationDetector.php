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

        $from = now()->subDays($windowDays);

        $locationQuery = LocationSample::query()
            ->where('user_id', $userId)
            ->where('recorded_at', '>=', $from);

        if (!$locationQuery->exists()) {
            return false;
        }

        // Ensure data actually spans the full window (not just today's data)
        $earliestSample = (clone $locationQuery)->min('recorded_at');
        if ($earliestSample && now()->diffInDays($earliestSample) < ($windowDays - 1)) {
            return false;
        }

        $locationCount = (clone $locationQuery)->distinct('place_id')->count('place_id');

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
