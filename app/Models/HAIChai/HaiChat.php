<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helpers;
use Illuminate\Support\Carbon;

class HaiChat extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    public static function getSingleChat($id = null)
    {
        return self::whereId($id)->first();
    }

    public static function getChat($days_old_chat = 0, $is_latest = 0, $admin_id = null)
    {

        $chats = self::query();

        if ($days_old_chat > 0){

            $chats = $chats->whereDate('created_at', '>', Carbon::now()->subDays($days_old_chat));

        }else{

            $chats = $chats->whereDate('created_at', Carbon::now()->subDays($days_old_chat));
        }

        if ($is_latest){

            $chats = $chats->latest();
        }

        $chats->when($admin_id, function ($q, $admin_id){

            $q->where(function ($q) use ($admin_id){

                $q->where(function ($query) use ($admin_id){

                    $query->where('admin_id', $admin_id)->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id));

                })->orWhere(function ($query){

                    $query->whereNull('admin_id')->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id));
                });

            });

        }, function ($q){

            $q->whereNull('admin_id')->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id));

        });

        $chats = $chats->get(['id','query','answer','likedislike']);

        return $chats;
    }

    public static function createChat($query = null, $reply = null, $admin_id = null, $likeDisLike = 0)
    {
        return self::create([
            'user_id' => (Helpers::getWebUser()->id ?? Helpers::getUser()->id),
            'query' => $query,
            'answer' => $reply ?? "",
            'likedislike' => 0,
            'admin_id' => $admin_id
        ]);
    }

    public static function updateChat($id = null, $likedislike = null)
    {
        return self::whereId($id)->update(['likedislike' => $likedislike]);
    }

    public static function likeDisLikeAiReply($request = null, $type = null){

        $chat = self::getSingleChat($request->input('chat_id'));

        if ($type === 'like'){

            HaiChat::updateChat($chat['id'], 2);

            $query = ClientQuery::create([
                'user_id' => Helpers::getUser()->id,
                'query' => $chat->query,
                'response' => 1,
                'chat_id' => $chat->id,
            ]);

            QueryAnswer::create([
                'query_id' => $query->id,
                'answer' => strip_tags($chat->answer),
            ]);

        }elseif ($type === 'dislike'){

            if ($chat['likedislike'] == 3 || $chat['likedislike'] == 2) {

                self::updateChat($request->input('chat_id'), 1);

            } else {

                HaiChat::updateChat($chat['id'], 1);
            }

        }

    }

    public static function deleteAdminChat($admin = null){

        if ($admin && ($admin['admin_id'] ?? false)){

            self::where('admin_id', $admin['admin_id'])->delete();

        }

    }

    public static function userLastMessage(){

        $convo = self::where('user_id', Helpers::getUser()->id)->latest()->first();

        if ($convo){

            return [
                [
                    'role' => 'user',
                    'content' => $convo['query'],
                ],
                [
                    'role' => 'assistant',
                    'content' => $convo['answer'],
                ]
            ];

        }

        return [[],[]];

    }
}
