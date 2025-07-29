<?php

namespace App\Models\Client\MultiMedia;

use App\Helpers\Helpers;
use App\Models\Upload\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiMediaStats extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    protected $appends = ['audio_url'];

    public function getAudioUrlAttribute()
    {

        return Helpers::getAudio($this->audio_id, 1);

    }

    public static function getPlayer($userId = null)
    {

        return self::where('user_id', $userId)->first();

    }
    public static function addOrUpdateRecentPlayer($data = null)
    {

        $user = Helpers::getUser();

        $getPlayer = self::getPlayer($user['id']);

        if (empty($getPlayer)) {

            return self::create($data);

        }else{

            return $getPlayer->update(['audio_id' => $data['audio_id'], 'time' => $data['time']]);
        }

    }
}
