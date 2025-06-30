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
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    protected $appends = ['audio_url'];

    public function getAudioUrlAttribute()
    {

        return Helpers::getAudio($this->audio_id, 1);
    }

    public static function getPodcast($perPage = 10)
    {
        return self::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public static function getAllAudioFiles()
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    public static function createPodcast($title = null, $audioId = null)
    {

        return self::create([
            'title' => $title,
            'audio_id' => $audioId
        ]);

    }

    public static function updatePodcastUrl($url = null){

        $podcast = self::where('user_id', Helpers::getWebUser()->id)->first();

        if ($podcast){

            $podcast->update(['embedded_url' => $url]);

        }else{

            self::create([
                'user_id' => Helpers::getWebUser()->id,
                'embedded_url' => $url,
            ]);
        }

    }
}
