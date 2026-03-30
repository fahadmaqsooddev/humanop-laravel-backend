<?php

namespace App\Models\v4\Client\Connection;

use App\Enums\Admin\Admin;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use App\Models\v4\Client\MessageThread\MessageThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\Connection\ConnectionRequest;
use App\Events\Connection\UnconnectRequest;
use App\Events\Connection\RequestAccept;
use App\Events\UserActionPerformed;
use App\Enum\UserActions\UserActions;

class Connection extends Model
{
    use HasFactory;

    protected $appends = [];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    // relation
    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function friend()
    {

        return $this->belongsTo(User::class, 'friend_id', 'id');
    }

    // appends
    public function getThreadIdAttribute()
    {

        return MessageThread::where(function ($q) {

            $q->where('sender_id', $this->friend_id)->where('receiver_id', (Helpers::getUser()->id ?? Helpers::getWebUser()->id));

        })->orWhere(function ($q) {

            $q->where('sender_id', (Helpers::getUser()->id ?? Helpers::getWebUser()->id))->where('receiver_id', $this->friend_id);

        })->first()->id ?? null;
    }


    // query
    public static function connectUnConnect($data = null)
    {

        $friend = User::getSingleUser($data['friend_id']);

        if ($data['type'] === 'connect') {

            $connection = self::where('user_id', $data['user_id'])->whereIn('status', [0, 1])->where('friend_id', $data['friend_id'])->exists();

            if (!$connection) {

                self::create($data);

                $msg = Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name . ' has Send You a Connection Request';

                event(new ConnectionRequest($data['friend_id'], 'Connection Request', $msg));

                ActivityLogger::addLog('Connection Request', "{$msg}");

                Notification::createNotification('connection request', $msg, $friend['device_token'], $friend['id'], 1, Admin::NETWORK_NOTIFICTAION,Admin::B2C_NOTIFICATION,Helpers::getUser()['id'],true);

                event(new UserActionPerformed(
                    $data['friend_id'],
                    UserActions::CONNECTION_REQUEST_SENT,
                    [
                        'sender_id' => $data['user_id'],
                        'sender_name' => Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name,
                    ]
                ));

                toastr()->success("connection request was sent");

            }

        } else if ($data['type'] === 'un-connect') {

            self::where(function ($q) use ($data) {

                $q->where('user_id', $data['user_id'])->where('friend_id', $data['friend_id']);

            })->orWhere(function ($q) use ($data) {

                $q->where('user_id', $data['friend_id'])->where('friend_id', $data['user_id']);

            })->delete();

            $msg = Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name . ' has disconnected your request';

            event(new UnconnectRequest($data['friend_id'], 'Dis-Connection Request', $msg));

            ActivityLogger::addLog('Connection Cancel', "{$msg}");

            Notification::createNotification('connection cancel', $msg, $friend['device_token'], $friend['id'], 1, Admin::NETWORK_NOTIFICTAION,Admin::B2C_NOTIFICATION,Helpers::getUser()['id'],true);

            event(new UserActionPerformed(
                $data['friend_id'],
                UserActions::CONNECTION_REMOVED,
                [
                    'sender_id' => $data['user_id'],
                    'sender_name' => Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name,
                ]
            ));

        } else if ($data['type'] === 'accept') {

            $received_request = self::where('user_id', $data['friend_id'])->where('friend_id', $data['user_id'])->first();

            $send_request = self::where('user_id', $data['user_id'])->where('friend_id', $data['friend_id'])->first();

            $user = Helpers::getUser();

            if ($received_request && !$send_request) {

                self::create([
                    'user_id' => $data['user_id'],
                    'friend_id' => $data['friend_id'],
                    'status' => 1,
                ]);

                $received_request->update(['status' => 1]);

                $msg = Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name . ' Has Accepted Your Request';

                event(new RequestAccept($data['friend_id'], 'Connection Request Accept', $msg));

                ActivityLogger::addLog('Connection Accept', "{$msg}");

                Notification::createNotification('connection accept', $msg, $user['device_token'], $friend['id'], 1, Admin::NETWORK_NOTIFICTAION,Admin::B2C_NOTIFICATION,Helpers::getUser()['id'],true);

                event(new UserActionPerformed(
                    $data['friend_id'],
                    UserActions::CONNECTION_ACCEPTED,
                    [
                        'sender_id' => $data['user_id'],
                        'sender_name' => Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name,
                    ]
                ));


            } elseif ($received_request && $send_request) {

                $received_request->update(['status' => 1]);

                $send_request->update(['status' => 1]);

                $msg = Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name . ' Has Accepted Your Request';

                event(new RequestAccept($data['friend_id'], 'Connection Request Accept', $msg));

                ActivityLogger::addLog('Connection Accept', "{$msg}");

                Notification::createNotification('connection accept', $msg, $user['device_token'], $friend['id'], 1, Admin::NETWORK_NOTIFICTAION,Admin::B2C_NOTIFICATION,Helpers::getUser()['id'],true);

                event(new UserActionPerformed(
                    $data['friend_id'],
                    UserActions::CONNECTION_ACCEPTED,
                    [
                        'sender_id' => $data['user_id'],
                        'sender_name' => Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name,
                    ]
                ));

            }

        }

    }

    public static function userPaginatedConnections($request = null)
    {

        $connections = self::query()
            ->has('user')
            ->whereHas('friend', function ($q) {
                $q->whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])
                    ->whereNull('b2b_deleted_at');
            })
            ->with('friend:id,first_name,last_name,image_id,profile_privacy,is_admin,b2b_deleted_at')
            ->where('user_id', Helpers::getUser()->id)
            ->where('status', 1)
            ->when($request->input('name'), function ($q, $name) {
                $q->whereHas('friend', function ($q) use ($name) {
                    $q->where('first_name', 'LIKE', "%{$name}%")
                        ->orWhere('last_name', 'LIKE', "%{$name}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$name}%"]);
                });
            })
            ->latest();

        return Helpers::pagination($connections, $request->input('pagination'), $request->input('per_page'));

    }

    public static function userSearchConnections($request = null)
    {

        $connections = self::query()
            ->has('user')
            ->whereHas('friend', function ($q) {
                $q->whereIn('profile_privacy', [1,2])
                    ->whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])
                    ->whereNull('b2b_deleted_at');
            })
            ->with('friend')
            ->where('user_id', Helpers::getUser()->id)
            ->where('status', 1)
            ->when($request->input('name'), function ($q, $name) {
                $q->whereHas('friend', function ($q) use ($name) {
                    $q->where('first_name', 'LIKE', "%{$name}%")
                        ->orWhere('last_name', 'LIKE', "%{$name}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$name}%"]);
                });
            })
            ->latest();

//        dd($connections->get());

        $connections =  Helpers::pagination($connections, $request->input('pagination'), $request->input('per_page'));

        $users = [];

        foreach ($connections as $connection) {

            $users[] = $connection->friend;
        }

        return array('data' => $users);
    }

    public static function connectionExists($friend_id = null)
    {

        $user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        return self::where('user_id', $user_id)
            ->where('status', 1)
            ->where('friend_id', $friend_id)->exists();
    }

    public static function paginatedConnectionRequests($request = null)
    {
        $connectionRequests = self::query()
            ->has('user')
            ->whereHas('user', function ($q) {
                $q->whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])
                    ->whereNull('b2b_deleted_at');
            })
            ->with('user:id,first_name,last_name,image_id,profile_privacy,is_admin,b2b_deleted_at')
            ->where('friend_id', Helpers::getUser()->id)
            ->where('status', 0)
            ->when($request->input('name'), function ($q, $name) {
                $q->whereHas('user', function ($q) use ($name) {
                    $q->where('first_name', 'LIKE', "%{$name}%")
                        ->orWhere('last_name', 'LIKE', "%{$name}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$name}%"]);
                });
            })
            ->latest();

        return Helpers::pagination($connectionRequests, $request->input('pagination'), $request->input('per_page'));
    }

    public static function allMatchingConnections($request = null, $loginUser = null)
    {
        $searchName = $request->input('search_name');

        // Step 1: Get user IDs from connection requests matching criteria
        $userIds = self::query()
            ->where('friend_id', $loginUser['id'])
            ->where('status', 0)
            ->when($searchName, function ($q) use ($searchName) {
                $q->whereHas('user', function ($q) use ($searchName) {
                    $q->where('first_name', 'LIKE', "%$searchName%")
                        ->orWhere('last_name', 'LIKE', "%$searchName%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$searchName%"]);
                });
            })
            ->with('user:id,first_name,last_name,is_admin,profile_privacy,b2b_deleted_at') // preload only needed fields
            ->get()
            ->pluck('user')
            ->filter(function ($user) {
                return in_array($user->is_admin, [Admin::IS_CUSTOMER, Admin::IS_B2B])
                    && in_array($user->profile_privacy, [1, 2])
                    && is_null($user->b2b_deleted_at);
            })
            ->values();

       return Helpers::matchingUsers($userIds, $loginUser);

    }
//    public static function allMatchingConnections($request = null, $loginUser = null)
//    {
//
//        $connectionRequests = self::query()
//            ->has('user')
//            ->with('user')
//            ->where('friend_id', Helpers::getUser()->id)
//            ->where('status', 0)
//            ->when($request->input('search_name'), function ($q, $name) {
//                $q->whereHas('user', function ($q) use ($name) {
//                    $q->where('first_name', 'LIKE', "%$name%")
//                        ->orWhere('last_name', 'LIKE', "%$name%")
//                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$name%"]);
//                });
//            })
//            ->latest()
//            ->get();
//
//        $users = $connectionRequests->pluck('user')->filter(function ($user) {
//            return in_array($user->is_admin, [Admin::IS_CUSTOMER, Admin::IS_B2B])
//                && ($user->profile_privacy == 2 || $user->profile_privacy == 1)
//                && is_null($user->b2b_deleted_at);
//        });
//
//       return Helpers::matchingUsers($users, $loginUser);
//
//    }

    public static function userConnectionIdsForHAi()
    {

        $user_connection_ids = self::whereHas('friend', function ($q) {

            $q->where('hai_status', 0);

        })->where('user_id', Helpers::getUser()->id)->where('status', 1)->get()->pluck('friend_id');

        return $user_connection_ids;

    }

    public static function createUserFetchConnection($userId = null, $connections = null)
    {

        foreach ($connections as $connection) {

            $connection['user_id'] = $userId;

            self::create($connection);
        }

        return true;

    }

}
