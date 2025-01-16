<?php

namespace App\Models\HAIChai;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getConversation($chat_bot_id = null, $user_id = null)
    {
        $user_id = empty($user_id) ? null : $user_id;

        return self::where('chat_bot_id', $chat_bot_id)->where('user_id', $user_id)->get();
    }

    public static function createConversation($chat_bot_id = null, $message = null,$reply = null,$user_id = null)
    {
        return self::create([
            'chat_bot_id' => $chat_bot_id,
            'message' => $message,
            'reply' => $reply,
            'user_id' => $user_id
        ]);
    }

    public static function updateConversation($chatBot = null, $reply = null)
    {
        return self::where('chatbot', $chatBot)->update(['reply' => $reply]);
    }

    public static function deleteOldChat(){

        self::whereDate('created_at', '<',Carbon::now()->subDays(30))->delete();
    }

    public static function singleConversation($id){

        $conversation = self::whereId($id)->first();

        if ($conversation){

            $conversation->update(['is_liked' => 1]);
        }

        return $conversation;
    }
}
