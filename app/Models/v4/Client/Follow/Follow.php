<?php

namespace App\Models\v4\Client\Follow;

use App\Enums\Admin\Admin;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\Helpers;
use App\Models\Admin\Notification\Notification;
use App\Models\v4\Client\MessageThread\MessageThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\Follow\FollowRequest;
use App\Events\UserActionPerformed;
use App\Events\Follow\UnFollowRequest;
use App\Enums\UserActions\UserActions;
use App\Services\v4\UserActionService;
class Follow extends Model
{
    use HasFactory;

    protected $appends = ['chat_id'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // appends
    public function getChatIdAttribute(){

        $user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        $chatId1 = $this->followerChatId()->where('sender_id', $this->follow_id)->first();

        if ($chatId1){ // follower

            return $chatId1->id;

        }else{

            $chatId2 = $this->followingChatId()->where('sender_id', $user_id)->first();

            if ($chatId2){ // following

                return $chatId2->id;
            }

            return null;

        }

    }


    // relations
    public function follower(){

        return $this->belongsTo(User::class,'follow_id','id');
    }

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }

    public function followerChatId(){

        return $this->hasMany(MessageThread::class,'receiver_id', 'user_id');
    }

    public function followingChatId(){

        return $this->hasMany(MessageThread::class, 'receiver_id', 'follow_id');
    }

    public static function followUnFollowForApi($request = null){

        $data['follow_id'] = $request['follow_id'];

        $data['user_id'] = Helpers::getUser()->id;

        if ($request['type'] === 'follow'){

            $follow = self::where('follow_id', $data['follow_id'])->where('user_id', $data['user_id'])->exists();

            if (!$follow){

                self::create($data);

                $msg = Helpers::getUser()?->first_name . ' ' . Helpers::getUser()?->last_name. ' has Started Following You';

                event(new FollowRequest($data['follow_id'],'Follow Request',$msg));

                ActivityLogger::addLog('Follow Request', "{$msg}");

                Notification::createNotification('follow request', $msg, null, $data['follow_id'], 1, Admin::NETWORK_NOTIFICTAION,Admin::B2C_NOTIFICATION,Helpers::getUser()['id'],true);

                UserActionService::dispatch(
                    $data['follow_id'], // user receiving the action
                    UserActions::FOLLOWED,
                    [
                        'follower_id' => $data['user_id'],
                        'follower_name' => Helpers::getUser()?->first_name . ' ' . Helpers::getUser()?->last_name,
                    ]
                );

            }

        }else if ($request['type'] === "un-follow"){

            self::where('follow_id', $data['follow_id'])->where('user_id', $data['user_id'])->delete();

            $msg = Helpers::getUser()?->first_name . ' ' . Helpers::getUser()?->last_name . ' has Un-Followed You';

            event(new UnFollowRequest($data['follow_id'],'Un-Follow Request',$msg));

            ActivityLogger::addLog('Unfollow Request', "{$msg}");

            Notification::createNotification('un follow request', $msg, null, $data['follow_id'], 1, Admin::NETWORK_NOTIFICTAION,Admin::B2C_NOTIFICATION,Helpers::getUser()['id'],true);

             // ✅ Updated to use UserActionService
            UserActionService::dispatch(
                $data['follow_id'], // user receiving the action
                UserActions::UNFOLLOWED,
                [
                    'follower_id' => $data['user_id'],
                    'follower_name' => Helpers::getUser()?->first_name . ' ' . Helpers::getUser()?->last_name,
                ]
            );

        }

    }

    public static function paginatedFollowing($request = null){

        $following = self::query();

        $following = $following->when($request->input('name'), function ($q, $search_name){

            $q->whereHas('follower', function ($q) use ($search_name){

                $q->where('first_name', 'LIKE', "%$search_name%")

                    ->orWhere('last_name', 'LIKE', "%$search_name%")

                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");

            });
        });


        $following = $following->has('follower')->where('user_id', Helpers::getUser()->id)

            ->with('follower:id,first_name,last_name,image_id');

        return Helpers::pagination($following, $request->input('pagination'), $request->input('per_page'));
    }

    public static function paginatedFollowers($request = null){

        $followers = self::query();

        $followers = $followers->when($request->input('name'), function ($q, $search_name){

            $q->whereHas('user', function ($q) use ($search_name){

                $q->where('first_name', 'LIKE', "%$search_name%")

                    ->orWhere('last_name', 'LIKE', "%$search_name%")

                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");

            });

        });

        $followers = $followers->has('user')->where('follow_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))

            ->with('user:id,first_name,last_name,image_id');



        return Helpers::pagination($followers, $request->input('pagination'), $request->input('per_page'));
    }
}
