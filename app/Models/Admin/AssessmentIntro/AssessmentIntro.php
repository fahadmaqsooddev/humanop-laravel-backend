<?php

namespace App\Models\Admin\AssessmentIntro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentIntro extends Model
{
    use HasFactory;


    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function allIntro()
    {
        return self::all();
    }

    public static function createIntro($data = null)
    {

        self::create([
            'name' => $data['name'],
            'public_name' => $data['public_name'],
            'code' => $data['code'],
            'number' => $data['number'],
            'type' => $data['type'],
            'text' => $data['text'],
        ]);
    }

    public static function getSingleAssessmentIntro($id = null)
    {
        return self::find($id);
    }

    public static function updateIntro($data = null, $id = null)
    {
        $assesssment = self::find($id)->update($data);

        return $assesssment;

    }
}
