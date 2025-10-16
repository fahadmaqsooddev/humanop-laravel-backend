<?php

namespace App\Models\Client\MessageThreadParticipant;

use App\Models\Client\MessageThread\MessageThread;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThreadParticipant extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getSingleUser($loginUserId = null, $threadId = null)
    {
        return self::where('user_id', $loginUserId)->where('message_thread_id', $threadId)->first();
    }

    public static function changeRole($request = null)
    {
        $participant = self::getSingleUser($request['participant_id'], $request['thread_id']);

        $participant->update(['role' => $request['role']]);

        return $participant;

    }

    public static function removeUser($request = null)
    {
        $getUser = self::getSingleUser($request['user_id'], $request['thread_id']);

        if (!$getUser) {
            return false;
        }

        if ($getUser->role != 0) {
            $getUser->delete();
            return true;
        }

        $getUser->delete();

        MessageThread::where('id', $request['thread_id'])
            ->where('owner_id', $request['user_id'])
            ->update(['owner_id' => null]);

        return true;
    }

    public static function createUser($data = null)
    {
        $getUser = self::getSingleUser($data['member_id'], $data['thread_id']);

        if (empty($getUser)) {

            if ($data['accept_or_reject'] == 1) {

                self::create([
                    'message_thread_id' => $data['thread_id'],
                    'user_id' => $data['member_id'],
                    'role' => 2,
                    'joined_at' => Carbon::now(),
                ]);
            }

            return true;

        } else {
            return false;
        }
    }

}
