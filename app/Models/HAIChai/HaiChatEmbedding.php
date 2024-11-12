<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaiChatEmbedding extends Model
{
    use HasFactory;
    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function singleEmbedding($id)
    {
        return self::whereId($id)->first();
    }

    public static function allEmbeddings()
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    public static function createEmbedding($name = null, $request_id = null)
    {
        return self::create([
            'name' => $name,
            'request_id' => $request_id,
        ]);
    }

    public static function deleteEmbedding($id)
    {
        return self::whereId($id)->delete();
    }

}
