<?php

namespace App\Models\v4\Client\MessageThread;

use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\Helpers;
use App\Models\v4\Client\Message\Message;
use App\Models\User;
use App\Services\Chat\DirectThread;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MessageThread extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['user_data', 'group_profile_url','is_owner','request_sent','is_group_joined'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    // Types
    public const TYPE_DIRECT = 0;
    public const TYPE_GROUP = 1;

    // Roles
    public const ROLE_OWNER = 0;
    public const ROLE_ADMIN = 1;
    public const ROLE_MEMBER = 2;


    // append
    public function getGroupProfileUrlAttribute()
    {

        if (!empty($this->group_icon_id)) {

            return Helpers::getImage($this->group_icon_id, 1)['url'];

        } else {

            return null;
        }

    }

    // relations
    public function sender()
    {

        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiver()
    {

        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function lastMessage()
    {

        return $this->hasOne(Message::class, 'message_thread_id', 'id')->latest();
    }

    public function unReadMessages()
    {

        return $this->hasMany(Message::class, 'message_thread_id', 'id')->where('is_read', 0);
    }

    

    // new relations
    public function messages()
    {
        return $this->hasMany(Message::class, 'message_thread_id', 'id')->orderByDesc('id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'message_thread_participants', 'message_thread_id', 'user_id')
            ->withPivot(['role', 'joined_at', 'muted_until'])
            ->withTimestamps();
    }

    public function groupChatRequests()
    {
        return $this->belongsToMany(User::class, 'message_thread_requests', 'thread_id', 'member_id');
    }

    public function scopeForUser($q, $userId)
    {
        return $q->where(function ($qq) use ($userId) {
            $qq->whereHas('participants', fn($p) => $p->where('user_id', $userId))
                ->orWhere(function ($legacy) use ($userId) {
                    $legacy->where('type', self::TYPE_DIRECT)
                        ->where(function ($w) use ($userId) {
                            $w->where('sender_id', $userId)->orWhere('receiver_id', $userId);
                        });
                });
        });
    }

    public function isGroup(): bool
    {
        return $this->type === self::TYPE_GROUP;
    }

    public function isDirect(): bool
    {
        return $this->type === self::TYPE_DIRECT;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    private function getUserId(){
       return $authId = Helpers::getUser()->id;
    }


    public function getRequestSentAttribute(): bool
    {
        return ($this->attributes['request_sent'] ?? 0) > 0;
    }

    public function getIsGroupJoinedAttribute(): bool
    {
        return ($this->attributes['is_group_joined'] ?? 0) > 0;
    }

    public function getIsOwnerAttribute(): bool
    {
        $authId=$this->getUserId();
        return (int) $this->owner_id === (int) $authId;
    }

    // appends
    public function getUserDataAttribute()
    {
        $authId = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        // Determine the other user in the thread
        if ($this->sender_id === $authId) {
            $user = $this->receiver;
        } elseif ($this->receiver_id === $authId) {
            $user = $this->sender;
        } else {
            return null;
        }

        if (!$user) {
            return null;
        }

        $latest = $this->lastMessage;
        $unread = $this->unread_messages_count ?? 0;


        $user->latest_message        = $latest->message ?? null;
        $user->latest_message_time   = $latest->created_at ?? null;
        $user->unread_messages_count = $unread;

        return $user;
    }

    // query
    public static function chats($name = null)
    {

        $chats = self::query();

        if ($name) {

            $chats = $chats->whereHas('sender', function ($q) use ($name) {

                $q->where(function ($query) use ($name) {

                    $query->where('first_name', 'LIKE', "%$name%")
                        ->orWhere('last_name', 'LIKE', "%$name%")
                        ->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");

                });

            })->orWhereHas('receiver', function ($q) use ($name) {

                $q->where(function ($query) use ($name) {

                    $query->where('first_name', 'LIKE', "%$name%")
                        ->orWhere('last_name', 'LIKE', "%$name%")
                        ->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");

                });

            });

        }

        $logged_in_user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        $chats = $chats->where(function ($q) {

            $q->has('sender')->orHas('receiver');

        })->where(function ($q) use ($logged_in_user_id) {

            $q->where('sender_id', $logged_in_user_id)
                ->orWhere('receiver_id', $logged_in_user_id);

        })
            ->orderBy('updated_at', 'DESC')
            ->with('lastMessage')
            ->withCount('unReadMessages')
            ->get();

        return $chats;
    }

    public static function createOrGetMessageThread($user_id = null)
    {

        $logged_in_user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        $message_thread = self::where(function ($q) use ($user_id) {

            $q->where('sender_id', $user_id)
                ->orWhere('receiver_id', $user_id);

        })->where(function ($q) use ($logged_in_user_id) {

            $q->where('sender_id', $logged_in_user_id)
                ->orWhere('receiver_id', $logged_in_user_id);

        })->first();

        if (!$message_thread) {

            $message_thread = self::create([
                'sender_id' => $logged_in_user_id,
                'receiver_id' => $user_id,
            ]);

        } else {

            $message_thread->update(['updated_at' => Carbon::now()]);
        }

        return $message_thread;

    }

    // public static function deleteMessageThread($user_id = null){

    //     $logged_in_user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

    //     self::where(function ($q) use ($user_id){

    //         $q->where('sender_id', $user_id)

    //             ->orWhere('receiver_id', $user_id);

    //     })->where(function ($q) use ($logged_in_user_id){

    //         $q->where('sender_id', $logged_in_user_id)

    //             ->orWhere('receiver_id', $logged_in_user_id);

    //     })->delete();

    // }

    public static function deleteMessageThreadFromApi($thread_id = null)
    {
        self::whereId($thread_id)->delete();
    }

    public static function getAllMessageThread($request = null)
    {


        $q = self::query()
            ->whereNotNull('owner_id')
            ->with([
                'participants',
                'sender:id,first_name,last_name,image_id',
                'receiver:id,first_name,last_name,image_id',
                'lastMessage:id,message_thread_id,message,created_at',
            ])
            ->select([
                'id',
                'type',
                'name',
                'owner_id',
                'sender_id',
                'receiver_id',
                'updated_at',
                'group_icon_id',
                'thread_privacy'
            ]);

        if ($request->filled('type')) {
            $q->where('type', (int)$request->query('type'));
        }

        return Helpers::pagination($q->orderByDesc('id'), $request['pagination'], $request['per_page']);

    }

    public static function getMyMessageThread($request = null, $userId = null)
    {

        $q = self::query()
            ->where('owner_id', $userId)
            ->orWhereHas('participants', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
             ->select([
                'id',
                'type',
                'name',
                'owner_id',
                'sender_id',
                'receiver_id',
                'updated_at',
                'group_icon_id',
                'thread_privacy'
            ])
            ->with([
                'lastMessage:id,message_thread_id,message,created_at',
                'sender:id,first_name,last_name,image_id',
                'receiver:id,first_name,last_name,image_id',
            ])
            ->withCount([
                'messages as unread_messages_count' => function ($q) use ($userId) {
                    $q->where('sender_id', '!=', $userId)
                        ->where('is_read', 0);
                },
                 // Check if the user has sent a group request
                'groupChatRequests as request_sent' => function ($q) use ($userId) {
                    $q->where('member_id', $userId);
                },
                // Check if the user is already a participant
                'participants as is_group_joined' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }
            ]);
        if ($request->filled('type')) {
            $q->where('type', (int)$request->query('type'));
        }

        return Helpers::pagination($q->orderByDesc('id'), $request['pagination'], $request['per_page']);

    }

    

    public static function getAllDirectMessageThread($request = null, $userId = null)
    {

        $type   = $request?->query('type');

        $authId = $userId;

        $q = self::query()
            ->select([
                'id',
                'type',
                'name',
                'owner_id',
                'sender_id',
                'receiver_id',
                'updated_at',
                'group_icon_id',
                'thread_privacy',
            ])
            ->with([
                'lastMessage:id,message_thread_id,message,created_at',
                'sender:id,first_name,last_name,image_id',
                'receiver:id,first_name,last_name,image_id',
                'participants',
            ])
            ->withCount([
                'messages as unread_messages_count' => function ($q) use ($authId) {
                    $q->where('sender_id', '!=', $authId)
                        ->where('is_read', 0);
                }
            ])
            ->where(function ($query) use ($userId, $type) {
                $query->where(function ($sub) use ($userId) {
                    $sub->where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId);
                })

                    ->when(!is_null($type), function ($q) use ($type) {
                        $q->where('type', (int) $type);
                    });
            });

        return Helpers::pagination(
            $q->orderByDesc('id'),
            $request['pagination'] ?? null,
            $request['per_page'] ?? null
        );
    }

    public static function createGroup($request = null, $ownerId = null)
    {

        $group = self::create([
            'type' => self::TYPE_GROUP,
            'name' => $request->string('name'),
            'owner_id' => $ownerId,
            'group_icon_id' => $request['group_icon_id'],
            'thread_privacy' => $request['thread_privacy']
        ]);

        $ids = collect($request->input('member_ids', []))->unique()->reject(fn($id) => $id == $ownerId);

        $group->participants()->attach($ownerId, ['role' => self::ROLE_OWNER, 'joined_at' => now()]);

        if ($ids->isNotEmpty()) {

            $group->participants()->attach($ids->all(), [

                'role' => self::ROLE_MEMBER, 'joined_at' => now()

            ]);

        }

        return $group->fresh(['participants:id,first_name,last_name']);

    }

    public static function editGroup($request = null, $ownerId = null)
    {

        $group = self::where('id', $request['thread_id'])->where('owner_id', $ownerId)->first();

        if (!empty($request['group_icon_id'])) {

            $group->update([
                'name' => $request->string('name'),
                'group_icon_id' => $request['group_icon_id'],
                'thread_privacy' => $request['thread_privacy'],
            ]);

        } else {

            $group->update([
                'name' => $request->string('name'),
                'thread_privacy' => $request['thread_privacy'],
            ]);
        }


        return $group->fresh(['participants:id,first_name,last_name']);

    }

    public static function addUsers($request = null)
    {
        $messageThread = self::find($request['thread_id']);

        if (!$messageThread) {
            throw new \Exception('Message thread not found.');
        }

        $ids = collect($request->input('user_ids', []))->unique()->values();

        if ($ids->isEmpty()) {
            throw new \Exception('No valid user IDs provided.');
        }

        $payload = $ids->mapWithKeys(function ($id) {

            return [
                $id => [
                    'role' => self::ROLE_MEMBER,
                    'joined_at' => now(),
                ],
            ];
        })->all();

        $messageThread->participants()->syncWithoutDetaching($payload);

        $addedMembers = User::whereIn('id', $ids)->get();

        foreach ($addedMembers as $member) {

            $fullName = "{$member->first_name} {$member->last_name}";

            ActivityLogger::addLog('Member Added to Group', "Member {$fullName} has been added to the group {$messageThread->name}.");
        }

        return $messageThread->load('participants:id,first_name,last_name');
    }

    public static function removeUser($request = null, $messageThread = null)
    {
        $messageThread->participants()->detach($request['member_id']);

        return true;
    }

    public static function directChat($userId = null)
    {

        abort_if($userId === Helpers::getUser()->id, 422, 'Cannot open direct chat with yourself.');

        $thread = DirectThread::findOrCreate(Helpers::getUser()->id, $userId);

        return response()->json([
            'id' => $thread->id,
            'type' => $thread->type,
            'members' => $thread->participants()->select('users.id', 'users.first_name as name')->get()
        ]);

    }

    public static function LoadMessageThreads($messageThread = null)
    {
        $messageThread->load([
            'participants:id,first_name,last_name,image_id',
            'owner:id,first_name,last_name,image_id',
            'groupChatRequests:id,first_name,last_name,image_id',
        ]);

        return $messageThread;
    }

    public static function checkMemberExistInGroup($data = null)
    {
        return self::with(['participants' => function ($q) use ($data) {
            $q->whereIn('user_id', (array)$data['member_id']);
        }])
            ->where('id', $data['thread_id'])
            ->where('owner_id', $data['owner_id'])
            ->first();
    }

    public static function allGroups($user = null)
    {
        return self::where('owner_id', $user['id'])->where('type', 1)->get();
    }

}
