<?php

namespace App\Policies;

use App\Models\Client\MessageThread\MessageThread;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessageThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, MessageThread $thread): bool
    {
        return $thread->participants()->where('user_id', $user->id)->exists()
            || ($thread->isDirect() && ($thread->sender_id === $user->id || $thread->receiver_id === $user->id));
    }

    public function manage(User $user, MessageThread $thread): bool
    {
        if ($thread->isDirect()) return false;
        if ($thread->owner_id === $user->id) return true;

        return $thread->participants()
            ->where('user_id', $user->id)
            ->wherePivot('role', MessageThread::ROLE_ADMIN)
            ->exists();
    }
}
