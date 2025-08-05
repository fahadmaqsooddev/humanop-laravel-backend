<?php

namespace App\Models\Admin\Code;

use App\Helpers\Helpers;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultVideo extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    protected $appends = ['video_upload_url', 'video_url'];

    public function getVideoUploadUrlAttribute()
    {
        return Helpers::getVideo($this->video_upload_id, 1, null);
    }

    public function getVideoUrlAttribute()
    {

        if (!empty($this->video)) {

            return asset('assets/video') . '/' . $this->video;
        }
    }

    public static function getSingleVideo($id = null)
    {
        return self::where('id', $id)->first();
    }

    public static function allVideos()
    {
        return self::all();
    }
}
