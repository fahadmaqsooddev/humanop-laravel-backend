<?php

namespace App\Models\Admin\Code;

use App\Helpers\Helpers;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    protected $appends = ['video_upload_url', 'video_url', 'image_url'];

    public function getVideoUploadUrlAttribute()
    {

        if (!empty($this->video_embed_link)){

            return $this->video_embed_link;

        }else{

            return null;

        }

    }

    public function getVideoUrlAttribute()
    {

        if (!empty($this->video)) {

            return asset('assets/video') . '/' . $this->video;
        }
    }

    public function getImageUrlAttribute()
    {
        if (!empty($this->image_id)) {
            return Helpers::getImage($this->image_id);
        }

        return null;
    }

    public static function getSingleVideo($id = null)
    {
        return self::where('id', $id)->first();
    }

    public static function allVideos()
    {
        return self::all();
    }

     public static function getVideoByName(string $publicName)
    {

        $record = self::where('public_name', $publicName)->first();

        if (!$record) {
            return null;
        }

        return [
            'slug_name' => $record->slug_name,
            'interval'   => $record->public_name,
            'video_url'  => $record->video_embed_link,
            'video_name' => $record->video,
            'image_url'  => $record->image_url ? $record->image_url['thumbnail_url'] : ''
        ];
    }
}
