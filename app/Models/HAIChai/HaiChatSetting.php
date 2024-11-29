<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaiChatSetting extends Model
{
    use HasFactory;

    CONST GPT_4o_MINI = 1;
    CONST GPT_4o = 2;
    CONST CLAUDE_Sonnet = 3;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    public static function getHaiChatSetting($chat_bot_id = null)
    {
        return self::where('chat_bot_id', $chat_bot_id)->first();
    }

    public static function updateHaiChatSetting($temperature = null, $max_token = null, $chunk = null, $model_type = null, $chat_bot_id = null)
    {
        $setting = self::where('chat_bot_id', $chat_bot_id)->first();

        if ($setting){

            $setting->update([
                'temperature' => $temperature,
                'max_token' => $max_token,
                'chunk' => $chunk,
                'model_type' => $model_type,
            ]);

        }else{

            self::create([
                'temperature' => $temperature,
                'max_token' => $max_token,
                'chunk' => $chunk,
                'model_type' => $model_type,
                'chat_bot_id' => $chat_bot_id,
            ]);

        }
    }
}
