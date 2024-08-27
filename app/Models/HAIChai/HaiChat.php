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

    public static function getChat()
    {
        return self::whereDate('created_at', Carbon::today())
            ->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))
            ->get(['id','query','answer','likedislike']);
    }

    public static function createChat($query = null, $reply = null)
    {
        return self::create([
            'user_id' => (Helpers::getWebUser()->id ?? Helpers::getUser()->id),
            'query' => $query,
            'answer' => $reply[0],
            'likedislike' => $reply[1],
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

        }elseif ($type === 'dislike'){

            if ($chat['likedislike'] == 3 || $chat['likedislike'] == 2) {

                self::updateChat($request->input('chat_id'), 1);

            } else {

                HaiChat::updateChat($chat['id'], 0);
            }

        }

    }
}
