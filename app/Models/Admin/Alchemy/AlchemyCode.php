<?php

namespace App\Models\Admin\Alchemy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlchemyCode extends Model
{
    use HasFactory;

    protected $appends = ['image_url','video_url'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // append
    public function getImageUrlAttribute(){

        return asset('assets') . '/' . $this->image;

    }

    public function getVideoUrlAttribute(){

        return asset('assets/video') .'/'. $this->video;

    }


    // query
    public static function getCodeDeatil($code)
    {
        return self::where('number', $code)->first();
    }

    public static function getNumbersFromCode($code = null){

        return self::where('code', $code)->pluck('number')->toArray();
    }
}
