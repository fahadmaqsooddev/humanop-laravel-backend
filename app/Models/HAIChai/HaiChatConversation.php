<?php

namespace App\Models\HAIChai;

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

    public static function getConversation($chatBot = null)
    {
        return self::where('chatbot', $chatBot)->get();
    }

    public static function createConversation($chatBot = null, $message = null,$reply = null)
    {
        return self::create([
            'chatbot' => $chatBot,
            'message' => $message,
            'reply' => $reply
        ]);
    }

    public static function updateConversation($chatBot = null, $reply = null)
    {
        return self::where('chatbot', $chatBot)->update(['reply' => $reply]);
    }
}
