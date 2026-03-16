<?php

namespace App\Services\v4;

use App\Models\v4\Client\Event;

class EventService
{

    public function create(
        int    $userId,
        string $type,
        string $protocol,
        array  $inputs
    )
    {

        $cooldown = config("humanop.event_cooldowns.$type", 5);

        $recentEvent = Event::query()
            ->where('user_id', $userId)
            ->where('event_type', $type)
            ->where('detected_at', '>', now()->subMinutes(0))
            ->exists();

        if ($recentEvent) {
            return null;
        }

        $event = Event::create([

            'user_id' => $userId,

            'event_type' => $type,

            'recommended_protocol' => $protocol,

            'inputs_snapshot' => $inputs,

            'detected_at' => now(),

            'expires_at' => now()->addMinutes(10)

        ]);

        app(EnergyShieldService::class)->applyEventDrain($userId, $type);

        return $event;

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
