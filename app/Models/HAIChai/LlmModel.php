<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LlmModel extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function createModel($data = null)
    {
        return self::create($data);
    }

    public static function GetModels(){

        return self::all();
    }

    public static function singleModel($id = null) {

        return self::whereId($id)->first();
    }

    public function Analytics()
    {
        return $this->hasMany(AnalyticsModel::class, 'llm_model_id');
    }
}
