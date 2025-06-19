<?php

namespace App\Models\HAIChai;

use FontLib\Table\Type\name;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chatbot extends Model
{
    use HasFactory, SoftDeletes;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    protected $appends = ['chat_bot_color'];

    //relations
    public function persona(){

        return $this->hasOne(ChatPrompt::class,'chat_bot_id','id');
    }

    public function restrictedKeywords(){

        return $this->hasMany(ChatbotKeyword::class,'chatbot_id','id');
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
    public static function createChatBot($name = null, $description = null, $brain_name = null)
    {
        return self::create([
            'name' => $name,
            'description' => $description,
            'brain_name' => $brain_name
        ]);
    }

    public static function allChats($brainName = null)
    {
        return self::when($brainName, function ($query, $name){

            $query->where('name', 'like', "%$name%");

        })->orderBy('created_at', 'desc')

            ->with('setting.plan')

            ->select(['id', 'name', 'description','is_published','brain_name'])

            ->get();
    }

    public static function singleChat($id = null)
    {
        return self::whereId($id)->first();
    }

    public static function getChatFromVendorName($vendor_name = null)
    {
        return self::where('name', $vendor_name)->first();
    }

    public static function deleteChat($id = null)
    {
        return self::whereId($id)->delete();
    }

    public static function updateChatBot($chat_bot_id = null, $description = null, $brain_name = null){

        self::whereId($chat_bot_id)->update(['description' => $description, 'brain_name' => $brain_name]);

    }

    public static function chatBots($brainName = null)
    {
        return self::when($brainName, function ($query, $name){

            $query->where('name', 'like', "%$name%");

        })->orderBy('created_at', 'desc')

            ->with('persona:chat_bot_id,persona_name,maestro_app')

            ->select(['id', 'name', 'description','is_connected'])

            ->get();
    }

    public static function createNewChatBot($name, $description, $max_tokens, $temperature, $chunks, $llm_model_id){

        return self::create([
            'name' => $name,
            'description' => $description,
            'max_tokens' => $max_tokens,
            'temperature' => $temperature,
            'chunks' => $chunks,
            'model_type' => $llm_model_id,
        ]);
    }

    public static function updateNewChatBot($chat_bot_id, $name, $description, $max_tokens, $temperature, $chunks, $llm_model_id){

        self::whereId($chat_bot_id)->update([
            'name' => $name,
            'description' => $description,
            'max_tokens' => $max_tokens,
            'temperature' => $temperature,
            'chunks' => $chunks,
            'model_type' => $llm_model_id,
        ]);

    }

    public static function singleChatBot($chat_bot_id){

        return self::whereId($chat_bot_id)->with('persona')->first();
    }
}
