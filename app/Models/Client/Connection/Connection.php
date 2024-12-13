<?php

namespace App\Models\Client\Connection;

use App\Helpers\Helpers;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $appends = [];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // relation
    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }

    public function friend(){

        return $this->belongsTo(User::class,'friend_id','id');
    }

    // appends
    public function getThreadIdAttribute(){

        return MessageThread::where(function ($q){

            $q->where('sender_id', $this->friend_id)->where('receiver_id', (Helpers::getUser()->id ?? Helpers::getWebUser()->id));

        })->orWhere(function ($q){

            $q->where('sender_id', (Helpers::getUser()->id ?? Helpers::getWebUser()->id))->where('receiver_id', $this->friend_id);

        })->first()->id ?? null;
    }


    // query
    public static function connectUnConnect($data = null){

        if ($data['type'] === 'connect'){

            $connection = self::where('user_id', $data['user_id'])->whereIn('status', [0,1])->where('friend_id', $data['friend_id'])->exists();

            if (!$connection){

                self::create($data);

                toastr()->success("connection request was sent");

            }

        }else if ($data['type'] === 'un-connect'){

            self::where(function ($q) use ($data){

                $q->where('user_id', $data['user_id'])->where('friend_id', $data['friend_id']);

            })->orWhere(function ($q) use($data){

                $q->where('user_id', $data['friend_id'])->where('friend_id', $data['user_id']);

            })->delete();

        }else if ($data['type'] === 'accept'){

            $received_request = self::where('user_id', $data['friend_id'])->where('friend_id', $data['user_id'])->first();

            $send_request = self::where('user_id', $data['user_id'])->where('friend_id', $data['friend_id'])->first();

            if ($received_request && !$send_request){

                self::create([
                    'user_id' => $data['user_id'],
                    'friend_id' => $data['friend_id'],
                    'status' => 1,
                ]);

                $received_request->update(['status' => 1]);

            }elseif ($received_request && $send_request){

                $received_request->update(['status' => 1]);

                $send_request->update(['status' => 1]);

            }

        }

    }

    public static function connectionRequests($name = null){

        $connection_requests = self::query();

        if (!empty($name)){

            $connection_requests = $connection_requests->whereHas('user', function ($q) use ($name){

                $q->where('first_name', 'LIKE', "%$name%")

                    ->orWhere('last_name', 'LIKE', "%$name%")

                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");

            });

        }

        $connection_requests = $connection_requests->has('user')

            ->with('user:id,first_name,last_name,image_id')

            ->where('friend_id', Helpers::getWebUser()->id)

            ->where('status', 0)

            ->latest()

            ->get();

        return $connection_requests;
    }

    public static function userConnections(){

        $user_id = Helpers::getWebUser()->id;

        return self::has('friend')->with('friend:id,first_name,last_name')->where('user_id', $user_id)

            ->where('status', 1)

            ->get();

    }

    public static function userPaginatedConnections($request = null){

        $name = $request->query('name');

        $user_id = Helpers::getUser()->id;

        $connections = self::whereHas('friend', function ($q) use ($name){

            $q->where(function ($query) use ($name){

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

    public static function connectionExists($friend_id = null){

        $user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        return self::where('user_id', $user_id)

            ->where('status', 1)

            ->where('friend_id', $friend_id)->exists();
    }

    public static function paginatedConnectionRequests($request = null){

        $connection_requests = self::query();

        $connection_requests = $connection_requests->when($request->input('name'), function ($q, $name){

            $q->whereHas('user', function ($q) use ($name){

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
