<?php

namespace App\Models\Client\Connection;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Admin\Notification\Notification;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\Connection\ConnectionRequest;
use App\Events\Connection\UnconnectRequest;
use App\Events\Connection\RequestAccept;

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

                // $msg = 'Connection Request send it';
            

    
    $msg= Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name. 
    ' has Send You a Connection Request';


                event(new ConnectionRequest($data['friend_id'], 'Connection Request', $msg));
                
                
                Helpers::OneSignalApiUsed($data['friend_id'], 'Connection Request', $msg);
                Notification::createNotification('connection request', $msg, $friend['device_token'], $friend['id'], 1, Admin::CONNECTION_REQUEST_NOTIFICATION);

                toastr()->success("connection request was sent");

            }

        } else if ($data['type'] === 'un-connect') {

            self::where(function ($q) use ($data) {

                $q->where('user_id', $data['user_id'])->where('friend_id', $data['friend_id']);

            })->orWhere(function ($q) use ($data) {

                $q->where('user_id', $data['friend_id'])->where('friend_id', $data['user_id']);

            })->delete();

            // $msg = 'Dis-Connect Request send it';
           

    $msg= Helpers::getUser()->first_name . ' ' . Helpers::getUser()?->last_name. 
    ' has disconnected your request';

    


            event(new UnconnectRequest($data['friend_id'], 'Dis-Connection Request', $msg));
         
            Helpers::OneSignalApiUsed($data['friend_id'], 'Dis-Connection Request', $msg);
            Notification::createNotification('connection cancel', $msg, $friend['device_token'], $friend['id'], 1, Admin::CONNECTION_CANCEL_NOTIFICATION);

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

                $friend = User::getSingleUser($data['friend_id']);
                // $msg = ' Your Connection Request Accepted';
                $msg =  $friend['first_name'].' '.$friend['last_name'].' Has Accepted Your Request';
               
                

                event(new RequestAccept($data['user_id'], 'Connection Request Accept', $msg));
                Helpers::OneSignalApiUsed($data['user_id'], 'Connection Request Accept', $msg);

                Notification::createNotification('connection accept', $msg, $user['device_token'], $user['id'], 1, Admin::CONNECTION_ACCEPT_NOTIFICATION);

            } elseif ($received_request && $send_request) {

                $received_request->update(['status' => 1]);

                $send_request->update(['status' => 1]);

                // $msg = 'Your Connection Request Accepted';
                $msg =  $friend['first_name'].' '.$friend['last_name'].' Has Accepted Your Request';

                event(new RequestAccept($data['user_id'], 'Connection Request Accept', $msg));
                Helpers::OneSignalApiUsed($data['user_id'], ' Connection Request Accept', $msg);
                Notification::createNotification('connection accept', $msg, $user['device_token'], $user['id'], 1, Admin::CONNECTION_ACCEPT_NOTIFICATION);

            }

        }

    }

    // public static function connectionRequests($name = null)
    // {

    //     $connection_requests = self::query();

    //     if (!empty($name)) {

    //         $connection_requests = $connection_requests->whereHas('user', function ($q) use ($name) {

    //             $q->where('first_name', 'LIKE', "%$name%")
    //                 ->orWhere('last_name', 'LIKE', "%$name%")
    //                 ->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");

    //         });

    //     }

    //     $connection_requests = $connection_requests->has('user')
    //         ->with('user:id,first_name,last_name,image_id')
    //         ->where('friend_id', Helpers::getWebUser()->id)
    //         ->where('status', 0)
    //         ->latest()
    //         ->get();

    //     return $connection_requests;
    // }

    // public static function userConnections()
    // {

    //     $user_id = Helpers::getWebUser()->id;

    //     return self::has('friend')->with('friend:id,first_name,last_name')->where('user_id', $user_id)
    //         ->where('status', 1)
    //         ->get();

    // }

    public static function userPaginatedConnections($request = null)
    {

        $name = $request->query('name');

        $user_id = Helpers::getUser()->id;

        $connections = self::whereHas('friend', function ($q) use ($name) {

            $q->where(function ($query) use ($name) {

                $query->where('first_name', 'LIKE', "%$name%")
                    ->orWhere('last_name', 'LIKE', "%$name%")
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");

            });
        })
            ->with('friend:id,first_name,last_name')
            ->where('user_id', $user_id)
            ->where('status', 1);

        return Helpers::pagination($connections, $request->input('pagination'), $request->input('per_page'));

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

        $connection_requests = self::query();

        $connection_requests = $connection_requests->when($request->input('name'), function ($q, $name) {

            $q->whereHas('user', function ($q) use ($name) {

                $q->where('first_name', 'LIKE', "%$name%")
                    ->orWhere('last_name', 'LIKE', "%$name%")
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");

            });

        });

        $connection_requests = $connection_requests->has('user')
            ->with('user:id,first_name,last_name,image_id')
            ->where('friend_id', Helpers::getUser()->id)
            ->where('status', 0)
            ->latest();

        return Helpers::pagination($connection_requests, $request->input('pagination'), $request->input('per_page'));
    }

}
