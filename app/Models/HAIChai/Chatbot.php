<?php

namespace App\Models\HAIChai;

use App\Helpers\Helpers;
use App\Models\Client\Plan\Plan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chatbot extends Model
{
    use HasFactory, SoftDeletes;

    CONST GPT_4o_MINI = 1;
    CONST GPT_4o = 2;
    CONST CLAUDE_Sonnet = 3;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    protected $appends = ['chat_bot_color'];

    // Relations
    public function plan(){

        return $this->belongsTo(Plan::class, 'plan_id','id');
    }

    // Appends
    public function getChatBotColorAttribute(){

        if($this->setting?->plan()?->first()?->name ?? false){

            if ($this->setting->plan()->first()->name === 'Freemium'){

                return '#F3DEBA';

            }elseif($this->setting->plan()->first()->name === 'Core'){

                return '#8BB1AB';

            }elseif ($this->setting->plan()->first()->name === 'Premium') {

                return '#1A7D9E';
            }
        }

        return '#F3DEBA';
    }

    // Relations
    public function setting(){

        return $this->hasOne(HaiChatSetting::class,'chat_bot_id','id');
    }


    // Queries
    public static function createChatBot($name = null, $description = null)
    {

        $formatted_name = str_replace(' ','', $name) .'_' . Carbon::now()->format('Y-m-d-H-i-s');

        self::create([
            'name' => $formatted_name,
            'description' => $description,
        ]);
    }

    public static function allChatBots()
    {
        return self::orderBy('created_at', 'desc')->select(['id', 'name', 'description'])->with('setting.plan')->get();
    }

//    public static function singleChat($id = null)
//    {
//        return self::whereId($id)->first();
//    }

    public static function singleChatBot($id = null)
    {
        return self::whereId($id)->first();
    }

    public static function getChatFromName($name = null)
    {
        return self::where('name', $name)->first();
    }

    public static function getChatFromVendorName($vendor_name = null)
    {

    public static function chatBotFromName($chat_bot_name = null)
    {
        return self::where('name', $chat_bot_name)->first();
    }

    public static function deleteChatBot($id = null)
    {
        self::whereId($id)->delete();
    }

    public static function updatePrompts($chat_bot_id, $prompt, $restriction){

        self::whereId($chat_bot_id)->update([
            'prompt' => $prompt,
            'restriction' => $restriction,
        ]);

    }

    public static function updateChatBotSettings($chat_bot_id, $temperature, $chunks, $max_token, $model_type, $plan_id){

        return tap(self::whereId($chat_bot_id)->first())->update([
            'temperature' => $temperature,
            'chunks' => $chunks,
            'max_tokens' => $max_token,
            'model_type' => $model_type,
            'plan_id' => $plan_id,
        ]);

    }

    public static function chatBotFromUserPlan(){

        return self::whereHas('plan',function ($query){

            $query->where('name', Helpers::getUser()->plan_name);

        })->latest()->first();
    }
}
