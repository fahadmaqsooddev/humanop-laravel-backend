<?php

namespace App\Models\Admin\Podcast;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helpers;

class Podcast extends Model
{
    use HasFactory;

    protected $appends = ['video_url'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // appends
    public function getVideoUrlAttribute(){

        return Helpers::getVideo($this->upload_id, 1);
    }

    public static function getPodcast()
    {
        return self::get()->last();
    }

    public static function createVideo($data = null)
    {
        $podcast = self::all()->last();

        if ($podcast)
        {
            $podcast->delete();

        }

        if ($data)
        {
            return self::create($data);
        }

    }
}
