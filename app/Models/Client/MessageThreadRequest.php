<?php

namespace App\Models\Client;

use App\Enums\Admin\Admin;
use App\Events\SendGroupRequest;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Models\Admin\Notification\Notification;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThreadRequest extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public static function getSingleGroupRequest($data = null)
    {
        return self::where('thread_id', $data['thread_id'])
            ->where('owner_id', $data['owner_id'])
            ->where('member_id', $data['member_id'])
            ->first();
    }

    public static function createGroupRequest($data = null)
    {
        $checkRequest = self::getSingleGroupRequest($data);

        if (empty($checkRequest)) {

            self::create([
                'thread_id' => $data['thread_id'],
                'owner_id' => $data['owner_id'],
                'member_id' => $data['member_id'],
            ]);

            $group = MessageThread::find($data['thread_id']);

            $member = User::getSingleUser($data['member_id']);

            $msg = "{$member['first_name']} {$member['last_name']} wants to join the {$group->name} group. He has sent a request. Would you like to add him to the group?";

            broadcast(new SendGroupRequest($data['owner_id'], 'Send Group Request', $msg))->toOthers();

            ActivityLogger::addLog('Send Group Request', "{$msg}");

            Notification::createNotification('Send Group Request', $msg, '', $data['owner_id'], 0, Admin::SEND_GROUP_REQUEST_NOTIFICATION, Admin::B2C_NOTIFICATION);

            return true;

        } else {

            return false;
        }

    }

    public static function deleteRequest($data = null)
    {
        return self::where('thread_id', $data['thread_id'])->where('member_id', $data['member_id'])->delete();
    }

}
