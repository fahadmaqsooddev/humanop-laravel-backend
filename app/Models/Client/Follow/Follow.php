<?php

namespace App\Models\Client\Follow;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public function follower(){

        return $this->belongsTo(User::class,'follow_id','id');
    }

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function addFollow($follow_id = null){

        $data['follow_id'] = $follow_id;

        $data['user_id'] = Helpers::getWebUser()->id;

        $follow = self::where('follow_id', $follow_id)->where('user_id', $data['user_id'])->first();

        $followUser = User::getSingleUser($follow_id);

        if ($follow) {

            $follow->delete();

            $message = "You unfollowed " . $followUser['first_name'] . " " . $followUser['last_name'] . ".";

            toastr()->success($message);

        } else {

            self::create($data);

            $message = "You followed " . $followUser['first_name'] . " " . $followUser['last_name'] . ".";

            toastr()->success($message);
        }


    }

    public static function following($search_name = null){

        $following = self::query();

        if ($search_name){

            $following = $following->whereHas('follower', function ($q) use ($search_name){

                $q->where('first_name', 'LIKE', "%$search_name%")

                    ->orWhere('last_name', 'LIKE', "%$search_name%")

                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");

            });

        }

        $following = $following->has('follower')->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))

            ->with('follower:id,first_name,last_name')

            ->get();

        return $following;
    }

    public static function followerExists($follow_id = null){

        return self::where('user_id', Helpers::getWebUser()->id)->where('follow_id', $follow_id)->exists();
    }

    public static function followers($search_name = null){

        $followers = self::query();

        if ($search_name){

            $followers = $followers->whereHas('user', function ($q) use ($search_name){

                $q->where('first_name', 'LIKE', "%$search_name%")

                    ->orWhere('last_name', 'LIKE', "%$search_name%")

                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");

            });
        }

        $followers = $followers->has('user')->where('follow_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))

            ->with('user:id,first_name,last_name,image_id')

            ->get();

        return $followers;
    }

    public static function followUnFollowForApi($request = null){

        $data['follow_id'] = $request['follow_id'];

        $data['user_id'] = Helpers::getUser()->id;

        if ($request['type'] === 'follow'){

            $follow = self::where('follow_id', $data['follow_id'])->where('user_id', $data['user_id'])->exists();

            if (!$follow){

                self::create($data);
            }

        }else if ($request['type'] === "un-follow"){

            self::where('follow_id', $data['follow_id'])->where('user_id', $data['user_id'])->delete();

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

            ->with('follower:id,first_name,last_name');

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
