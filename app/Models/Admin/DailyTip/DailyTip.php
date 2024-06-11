<?php

namespace App\Models\Admin\DailyTip;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipRecord;

class DailyTip extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getTip()
    {
        return self::get();
    }

    public static function createTip($data = null)
    {
        return self::create($data);
    }

    public static function getSingleTip($tiprecords = null)
    {

        $tip = self::whereNotIn('id', $tiprecords)->inRandomOrder()->first();


        if ($tip != null)
        {
            TipRecord::createTip($tip['id']);

            return $tip;
        }

    }

}
