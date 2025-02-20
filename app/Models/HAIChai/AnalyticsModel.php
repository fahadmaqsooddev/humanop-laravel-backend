<?php

namespace App\Models\HaiChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsModel extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function llmModel()
    {
        return $this->belongsTo(LlmModel::class, 'llm_model_id');
    }

    public  static function createAnalytics($message = null, $modelId = null, $token = null)
    {
        return self::create([
            'query' => $message,
            'prompt_token' => $token['prompt_tokens'],
            'completion_token' => $token['completion_tokens'],
            'total_token' => $token['total_tokens'],
            'llm_model_id' => $modelId,
        ]);
    }
    public static function getData($model_type=null){
        $model_id = LlmModel::where('model_value', $model_type)->value('id');
        return self::where('llm_model_id', $model_id)->get();
    }
}
