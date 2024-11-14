<?php

namespace App\Models\HAIChai;

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

    public static function createActiveEmbedding($bot_name = null, $request_id = null)
    {
        return self::create([
            'chat_bot' => $bot_name,
            'request_id' => $request_id,
        ]);
    }

    public static function singleActiveEmbedding($request_id = null)
    {
        return self::where('request_id', $request_id)->first();
    }

    public static function allActiveEmbeddings($bot_name = null)
    {
        return self::where('chat_bot', $bot_name)->orderBy('created_at', 'desc')->with('embedding')->get();
    }

    public static function allRequestIds($bot_name = null)
    {
        return self::where('chat_bot', $bot_name)->pluck('request_id')->toArray();
    }

    public static function deleteActiveEmbedding($request_id = null)
    {
        return self::where('request_id', $request_id)->delete();
    }

    public function embedding()
    {

        return $this->belongsTo(HaiChatEmbedding::class, 'request_id', 'request_id');
    }

    public static function getChatActiveEmbedding($chatName = null)
    {
        $requestIds = self::where('chat_bot', $chatName)->pluck('request_id')->toArray();

        return [
            'file_name' => $requestIds
        ];
    }
}
