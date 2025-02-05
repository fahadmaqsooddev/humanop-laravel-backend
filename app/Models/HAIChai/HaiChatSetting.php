<?php

namespace App\Models\HAIChai;

use App\Models\Client\Plan\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaiChatSetting extends Model
{
    use HasFactory;

    CONST GPT_4o_MINI = 1;
    CONST GPT_4o = 2;
    CONST CLAUDE_Sonnet = 3;
    CONST GPT_4o_FINE_TUNED = 4;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    // Relations
    public function plan(){

        return $this->belongsTo(Plan::class,'plan_id','id');
    }

    // Queries
    public static function getHaiChatSetting($chat_bot_id = null)
    {
        $setting = self::where('chat_bot_id', $chat_bot_id)->first();

        if (!$setting){

            $setting = self::updateHaiChatSetting(null,null,null,null,$chat_bot_id);
        }

        return $setting;
    }

    public static function updateHaiChatSetting($temperature = null, $max_token = null, $chunk = null, $model_type = null, $chat_bot_id = null, $plan_id = null)
    {
        $setting = self::where('chat_bot_id', $chat_bot_id)->first();

        $defaultPlanId = Plan::where('name', 'Freemium')->first()->id ?? null;

        if ($setting){

            $setting->update([
                'temperature' => $temperature,
                'max_token' => $max_token,
                'chunk' => $chunk,
                'model_type' => $model_type,
                'plan_id' => $plan_id ?? $defaultPlanId,
            ]);

        }else{

            return self::create([
                'temperature' => $temperature ?? 0.5,
                'max_token' => $max_token ?? 500,
                'chunk' => $chunk ?? 5,
                'model_type' => $model_type ?? self::GPT_4o_MINI,
                'chat_bot_id' => $chat_bot_id,
                'plan_id' => $plan_id ?? $defaultPlanId,
            ]);

        }
    }

    public static function duplicatingChatBotSetting($id, $newChatBotId){

        $chatBotSetting = self::whereId($id)->first();

        $newChatBot = $chatBotSetting->replicate();

        $newChatBot->chat_bot_id = $newChatBotId;

        $newChatBot->save();
    }
}
