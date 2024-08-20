<?php

namespace App\Models\Admin\Podcast;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helpers;

class Podcast extends Model
{
    use HasFactory;

    protected $appends = ['audio_url'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // appends
    public function getAudioUrlAttribute(){

        return Helpers::getAudio($this->upload_id, 1);
    }

    public static function getPodcast()
    {
        return self::latest()->first();
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

    public static function adminLatestPodcastUrl(){

        return self::where('user_id', Helpers::getWebUser()->id)->latest()->first();
    }
}
