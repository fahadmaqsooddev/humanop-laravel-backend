<?php

namespace App\Services\v4\Chat;

use App\Models\v4\Client\MessageThread\MessageThread;

class DirectThread
{
    public static function findOrCreate(int $userA, int $userB): MessageThread
    {
        [$u1, $u2] = $userA < $userB ? [$userA, $userB] : [$userB, $userA];
        $key = "{$u1}:{$u2}";

        $thread = MessageThread::firstOrCreate(
            ['direct_key' => $key],
            [
                'type' => MessageThread::TYPE_DIRECT,
                'sender_id' => $u1,
                'receiver_id' => $u2,
            ]
        );

        $thread->participants()->syncWithoutDetaching([
            $u1 => ['role' => MessageThread::ROLE_MEMBER, 'joined_at' => now()],
            $u2 => ['role' => MessageThread::ROLE_MEMBER, 'joined_at' => now()],
        ]);

        return $thread->fresh('participants');
    }
}
