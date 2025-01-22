<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatbotKeyword extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    // Queries
    public static function createChatbotKeyword($word, $chatbot_name, $message){

        $chatbot = Chatbot::getChatFromVendorName($chatbot_name);

        self::create([
            'word' => $word,
            'chatbot_id' => $chatbot->id ?? null,
            'message' => $message,
        ]);

    }

    public static function removeChatbotKeyword($id){

        self::whereId($id)->delete();

    }

    public static function chatbotKeywords($name){

        $chatbot = Chatbot::getChatFromVendorName($name);

        return self::where('chatbot_id', $chatbot->id ?? null)->get();
    }

    public static function checkChatBotKeywords($name, $query){

        $chatbot = Chatbot::getChatFromVendorName($name);

        $keywords = self::where('chatbot_id', $chatbot->id)->get();

        foreach ($keywords as $keyword){

            if (stripos($query, $keyword['word']) !== false){

                return $keyword['message'];

            }

        }

//        if (count($keywords) > 0){
//
//            $pattern = '/\b(' . implode('|', $keywords) . ')\b/i';
//
//            if (preg_match($pattern, $query)) {
//
//                return true;
//            }
//
//        }

        return false;
    }
}
