<?php

use Illuminate\Support\Facades\Broadcast;
use \App\Models\Client\MessageThread\MessageThread;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.thread.{id}', function ($user, $id) {
    $thread = MessageThread::find($id);
    if (!$thread) return false;

    $isMember = $thread->participants()->where('user_id',$user->id)->exists();
    $isDirect = $thread->isDirect() && ($thread->sender_id === $user->id || $thread->receiver_id === $user->id);
    return $isMember || $isDirect;
});

Broadcast::channel('presence.chat.thread.{id}', function ($user, $id) {
    $thread = MessageThread::find($id);
    if (!$thread) return false;

    $isMember = $thread->participants()->where('user_id',$user->id)->exists();
    $isDirect = $thread->isDirect() && ($thread->sender_id === $user->id || $thread->receiver_id === $user->id);

    return ($isMember || $isDirect) ? ['id'=> $user->id,'name'=> $user->first_name . ' ' . $user->last_name] : false;
});

Broadcast::channel('push-notification.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
    return true;
});
