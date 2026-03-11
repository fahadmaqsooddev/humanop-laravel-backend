<?php

namespace App\Services\v4;

use App\Models\v4\Client\Event;

class EventService
{

    public function create(
        int    $userId,
        string $eventType,
        string $recommendedProtocol,
        array  $inputsSnapshot
    ): Event
    {
        return Event::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'recommended_protocol' => $recommendedProtocol,
            'inputs_snapshot' => $inputsSnapshot,
            'detected_at' => now(),
        ]);
    }

    public function wasRecentlyDetected(int $userId, string $eventType, int $minutes = 15): bool
    {
        return Event::query()
            ->where('user_id', $userId)
            ->where('event_type', $eventType)
            ->where('detected_at', '>=', now()->subMinutes($minutes))
            ->exists();
    }

}
