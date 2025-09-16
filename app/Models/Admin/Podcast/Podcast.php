<?php

namespace App\Models\Admin\Podcast;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helpers;

class Podcast extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    protected $appends = ['audio_url', 'thumbnail_url'];

    public function getAudioUrlAttribute()
    {

        return Helpers::getAudio($this->audio_id, 1);
    }

    public function getThumbnailUrlAttribute()
    {

        if (!empty($this->thumbnail_id)) {


            $url =  Helpers::getImage($this->thumbnail_id);

            return $url['url'] ?? null;

        } else {

            return null;
        }

    }

    public static function singlePodcast($id = null)
    {
        return self::where('id', $id)->first();
    }

    public static function getPodcast($perPage = 10)
    {
        return self::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public static function getAllAudioFiles()
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    public static function createPodcast($title = null, $audioId = null, $thumbnailId = null)
    {

        return self::create([
            'title' => $title,
            'audio_id' => $audioId,
            'thumbnail_id' => $thumbnailId
        ]);

    }

    public static function updatePodcast($id = null, $title = null)
    {

        $podcast = self::where('id', $id)->first();

        if ($podcast) {

            $podcast->update(['title' => $title]);

        }

    }

    public static function deletePodcast($podcastId = null)
    {
        return self::where('id', $podcastId)->delete();
    }
}
