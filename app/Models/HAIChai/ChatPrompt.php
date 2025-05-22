<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatPrompt extends Model
{
    use HasFactory;
    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    // realtions
    public function chatbot(){

        return $this->belongsTo(Chatbot::class,'chat_bot_id','id');
    }


    // query
     public static function createUpdatePrompt($name = null, $prompt = null,$restriction = null, $keyword_restriction_message = null)
     {
        $existingPrompt =  self::singlePromptByName($name);
         if($existingPrompt){
             return self::where('id',$existingPrompt['id'])->update([
                 'name' => $name,
                 'prompt' => $prompt ?? null,
                 'restriction' => $restriction ?? null
             ]);
         }else{
             return self::create([
                 'name' => $name,
                 'prompt' => $prompt ?? null,
                 'restriction' => $restriction ?? null
             ]);
         }
     }

     public static function singlePromptByName($name = null)
     {
         return self::where('name',$name)->first();
     }

     public static function duplicatingChatBot($chat_bot_name, $new_chat_bot_name){

        $chatbot = self::where('name', $chat_bot_name)->first();

        if ($chatbot){

            $duplicateChatBot = $chatbot->replicate();
            $duplicateChatBot->name = $new_chat_bot_name;
            $duplicateChatBot->save();

        }

     }

    public static function duplicatingNewChatBot($chat_bot_id, $new_chat_bot_id){

        $chatbot = self::whereId($chat_bot_id)->first();

        if ($chatbot){

            $duplicateChatBot = $chatbot->replicate();
            $duplicateChatBot->name = $new_chat_bot_id;
            $duplicateChatBot->save();

        }

    }

    public static function createOrUpdatePersona($chat_bot_id, $personaName, $human_op_app, $maestro_app){

        $maestro_app_array = explode('-', $maestro_app);

        $persona = self::where('chat_bot_id', $chat_bot_id)->first();

        if ($persona){

            $persona->update([
                'human_op_app' => $human_op_app,
                'persona_name' => $personaName,
                'maestro_app' => !empty($maestro_app_array[0]) ? (int)$maestro_app_array[0] : (int)$maestro_app,
                'maestro_app_id' => isset($maestro_app_array[1]) ? (int)$maestro_app_array[1] : null,
            ]);

        }else{

            self::create([
                'chat_bot_id' => $chat_bot_id,
                'human_op_app' => $human_op_app,
                'persona_name' => $personaName,
                'maestro_app' => !empty($maestro_app_array[0]) ? (int)$maestro_app_array[0] : (int)$maestro_app,
                'maestro_app_id' => isset($maestro_app_array[1]) ? (int)$maestro_app_array[1] : null,
            ]);
        }

    }

    public static function singlePersona($chat_bot_id){

        return self::where('chat_bot_id', $chat_bot_id)->first();

    }

    public static function createOrUpdatePersonaText($chat_bot_id, $prompt, $restriction, $is_training){

        $persona = self::where('chat_bot_id', $chat_bot_id)->first();

        if ($persona){

            $persona->update([
                'prompt' => $prompt,
                'restriction' => $restriction,
                'is_training' => $is_training
            ]);

        }else{

            self::create([
                'chat_bot_id' => $chat_bot_id,
                'prompt' => $prompt,
                'restriction' => $restriction,
                'is_training' => $is_training
            ]);
        }

    }
}
