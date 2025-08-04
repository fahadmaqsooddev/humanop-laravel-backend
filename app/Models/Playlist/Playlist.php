<?php

namespace App\Models\Playlist;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function myPlaylists()
    {
        $user = Helpers::getUser();

        return self::where('user_id', $user['id'])->orderBy('created_at', 'desc')->get();

    }

    public static function addPlaylist($playlist = null)
    {
        return self::create($playlist);
    }


}
