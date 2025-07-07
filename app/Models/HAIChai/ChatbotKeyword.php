<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function checkChatBotKeywordsForApi($chat_bot_id, $question)
    {

        $keywords = self::where('chatbot_id', $chat_bot_id)->get();

        foreach ($keywords as $keyword) {

            if (stripos($question, $keyword['word']) !== false) {

                return $keyword['message'];

            }

        }

        return false;
    }

    public static function checkPublishedChatBotKeywords($keywords, $question)
    {

        foreach ($keywords as $keyword) {

            if (stripos($question, $keyword['word']) !== false) {

                return $keyword['message'];

            }

        }

        return false;
    }
}
