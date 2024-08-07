<?php

namespace App\Models\Client\MessageThread;

use App\Helpers\Helpers;
use App\Models\Client\Message\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageThread extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['user_data'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // relations
    public function sender(){

        return $this->belongsTo(User::class,'sender_id','id');
    }

    public function receiver(){

        return $this->belongsTo(User::class,'receiver_id','id');
    }

    public function lastMessage(){

        return $this->hasOne(Message::class,'message_thread_id','id')->latest();
    }

    public function unReadMessages(){

        return $this->hasMany(Message::class,'message_thread_id','id')->where('is_read', 0);
    }

    // appends
    public function getUserDataAttribute(){

        if ($this->sender_id === (Helpers::getWebUser()->id ?? Helpers::getUser()->id)){

            return $this->receiver()->select('id','first_name','last_name')->first();

        }else if ($this->receiver_id === (Helpers::getWebUser()->id || Helpers::getUser()->id)){

            return $this->sender()->select('id','first_name','last_name')->first();

        }else{

            return null;
        }

    }


    // query
    public static function chats($name = null){

        $chats = self::query();

        if ($name){

            $chats = $chats->whereHas('sender', function ($q) use ($name){

                $q->where(function ($query) use ($name){

                    $query->where('first_name', 'LIKE', "%$name%")

                        ->orWhere('last_name', 'LIKE', "%$name%")

                        ->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");

                });

            })->orWhereHas('receiver', function ($q) use ($name){

                $q->where(function ($query) use ($name){

                    $query->where('first_name', 'LIKE', "%$name%")

                        ->orWhere('last_name', 'LIKE', "%$name%")

                        ->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");

                });

            });

        }

        $logged_in_user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

         $chats = $chats->where(function ($q){

             $q->has('sender')->orHas('receiver');

         })->where(function ($q) use ($logged_in_user_id){

            $q->where('sender_id', $logged_in_user_id)

                ->orWhere('receiver_id', $logged_in_user_id);

        })
            ->orderBy('updated_at', 'DESC')

            ->with('lastMessage')

             ->withCount('unReadMessages')

            ->get();

        return $chats;
    }

    public static function createOrGetMessageThread($user_id = null){

        $logged_in_user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        $message_thread = self::where(function ($q) use ($user_id){

            $q->where('sender_id', $user_id)

                ->orWhere('receiver_id', $user_id);

        })->where(function ($q) use ($logged_in_user_id){

            $q->where('sender_id', $logged_in_user_id)

                ->orWhere('receiver_id', $logged_in_user_id);

        })->first();

        if (!$message_thread){

            $message_thread = self::create([
                'sender_id' => $logged_in_user_id,
                'receiver_id' => $user_id,
            ]);

        }else{

            $message_thread->update(['updated_at' => Carbon::now()]);
        }

        return $message_thread;

    }

    public static function deleteMessageThread($user_id = null){

        $logged_in_user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        self::where(function ($q) use ($user_id){

            $q->where('sender_id', $user_id)

                ->orWhere('receiver_id', $user_id);

        })->where(function ($q) use ($logged_in_user_id){

            $q->where('sender_id', $logged_in_user_id)

                ->orWhere('receiver_id', $logged_in_user_id);

        })->delete();

    }

    public static function deleteMessageThreadFromApi($thread_id = null){

        self::whereId($thread_id)->delete();

    }
}
