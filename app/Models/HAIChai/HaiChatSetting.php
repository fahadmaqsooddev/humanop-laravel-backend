<?php

namespace App\Models\HAIChai;

use App\Models\B2B\BusinessSubStrategies;
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

    public function businessSubStrategies(){

        return $this->hasMany(BusinessSubStrategies::class,'business_strategy_id','maestro_app_id');
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

    public static function updateHaiChatSetting($temperature = null, $max_token = null, $chunk = null, $model_id = null, $chat_bot_id = null, $plan_id = null)
    {
        $setting = self::where('chat_bot_id', $chat_bot_id)->first();

        $defaultPlanId = Plan::where('name', 'Freemium')->first()->id ?? null;

        if ($setting){

            $setting->update([
                'temperature' => $temperature,
                'max_token' => $max_token,
                'chunk' => $chunk,
                'model_type' => $model_id,
                'plan_id' => $plan_id ?? $defaultPlanId,
            ]);

        }else{

            return self::create([
                'temperature' => $temperature ?? 0.5,
                'max_token' => $max_token ?? 500,
                'chunk' => $chunk ?? 5,
                'model_type' => $model_id ?? 1,
                'chat_bot_id' => $chat_bot_id,
                'plan_id' => $plan_id ?? $defaultPlanId,
            ]);

        }
    }

    public static function duplicatingChatBotSetting($id, $newChatBotId){

        $chatBotSetting = self::where('chat_bot_id',$id)->first();

        if ($chatBotSetting){

            $newChatBot = $chatBotSetting->replicate();

            $newChatBot->chat_bot_id = $newChatBotId;

            $newChatBot->save();

        }
    }

    public static function updatePersonaConfigurations($chat_bot_id, $persona_text, $persona_name, $human_op_app, $maestro_app){

        $maestro_app_array = explode('-', $maestro_app);

        $haiSetting = self::getHaiChatSetting($chat_bot_id);

        if ($haiSetting){

            $haiSetting->update([
                'persona_text' => $persona_text,
                'persona_name' => $persona_name,
                'human_op_app' => $human_op_app,
                'maestro_app' => !empty($maestro_app_array[0]) ? (int)$maestro_app_array[0] : (int)$maestro_app,
                'maestro_app_id' => isset($maestro_app_array[1]) ? (int)$maestro_app_array[1] : null,
            ]);
        }

    }
}
