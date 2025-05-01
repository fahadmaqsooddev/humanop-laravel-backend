<?php

namespace App\Models\HAIChai;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Aws\flatmap;

class HaiChatConversation extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getConversation($chatBot = null, $user_id = null)
    {
        $user_id = empty($user_id) ? null : $user_id;

        return self::where('chatbot', $chatBot)->where('user_id', $user_id)->get();
    }

    public static function createConversation($chatBot = null, $message = null,$reply = null,$user_id = null)
    {
        return self::create([
            'chatbot' => $chatBot,
            'message' => $message,
            'reply' => $reply,
            'user_id' => $user_id
        ]);
    }

    public static function singleConversation($id){

        $conversation = self::whereId($id)->first();

        if ($conversation){

            $conversation->update(['is_liked' => 1]);
        }

        return $conversation;
    }

    public static function userLastMessage($chatBotName = null,$user_id = null){

        $convo = self::where('user_id', $user_id)->where('chatbot', $chatBotName)->latest()->first();

        if ($convo){

            return [
                [
                    "role" => "user",
                    "content" => $convo['message'],
                ],[
                    "role" => "assistant",
                    "content" => $convo['reply'],
                ]
            ];

        }

        return [[],[]];
    }
}
