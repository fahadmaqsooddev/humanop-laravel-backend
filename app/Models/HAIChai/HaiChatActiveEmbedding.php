<?php

namespace App\Models\HAIChai;

use App\Models\KnowledgeBase\KnowledgeBase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaiChatActiveEmbedding extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    // Realtions
    public function knowledgeBase(){

        return $this->belongsTo(KnowledgeBase::class,'knowledge_base_id','id');
    }

    public function embedding(){

        return $this->belongsTo(HaiChatEmbedding::class, 'request_id', 'request_id');
    }


    // Queries
    public static function createActiveEmbedding($chat_bot_id = null, $knowledge_base_id = null)
    {
        return self::create([
            'chat_bot_id' => $chat_bot_id,
            'knowledge_base_id' => $knowledge_base_id,
        ]);
    }

    public static function singleActiveEmbedding($knowledge_base_id = null, $chat_bot_id = null)
    {
        return self::where('knowledge_base_id', $knowledge_base_id)->where('chat_bot_id', $chat_bot_id)->first();
    }

//    public static function allActiveEmbeddings($bot_name = null)
//    {
//        return self::where('chat_bot', $bot_name)->orderBy('created_at', 'desc')->with('embedding')->get();
//    }

//    public static function allRequestIds($bot_name = null)
//    {
//        return self::where('chat_bot', $bot_name)->pluck('request_id')->toArray();
//    }

    public static function deleteActiveEmbedding($knowledge_base_id = null, $chat_bot_id = null){

        return self::where('knowledge_base_id', $knowledge_base_id)->where('chat_bot_id', $chat_bot_id)->delete();
    }

//    public static function getChatActiveEmbedding($chatName = null)
//    {
//        return self::where('chat_bot', $chatName)->pluck('request_id')->toArray();
//
//    }

    public static function activeEmbeddings($chat_bot_id)
    {
        return self::with('knowledgeBase')->where('chat_bot_id', $chat_bot_id)

            ->orderBy('created_at', 'desc')

            ->get()->pluck('knowledgeBase');

    }

//    public static function allActiveEmbeddingsIds($bot_name = null)
//    {
//        return self::with('knowledgeBase')->where('chat_bot', $bot_name)->orderBy('created_at', 'desc')->get()->pluck('knowledgeBase');
//    }
}
