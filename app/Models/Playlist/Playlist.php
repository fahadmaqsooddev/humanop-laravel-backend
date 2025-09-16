<?php

namespace App\Models\Playlist;

use App\Helpers\Helpers;
use App\Models\PlaylistLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    // Append

    public function getImageUrlAttribute()
    {

        if (!empty($this->image_id)) {

            return Helpers::getImage($this->image_id);

        } else {

            return null;
        }

    }

    public function playlist()
    {
        return $this->hasMany(PlaylistLog::class, 'playlist_id', 'id');
    }

    public static function myPlaylists()
    {
        $user = Helpers::getUser();

        return self::where('user_id', $user['id'])->with('playlist')->orderBy('created_at', 'desc')->get();

    }

    public static function newPlaylist($playlist = null)
    {
        return self::create($playlist);
    }

    public static function editPlaylist($playlist = null)
    {
        $getPlaylist = self::where('id', $playlist['playlist_id'])->first();

        return $getPlaylist->update([
            'title' => $playlist['title'] ?? $getPlaylist->title,
            'description' => $playlist['description'] ?? $getPlaylist->description,
            'image_id' => $playlist['image_id'] ?? $getPlaylist->image_id,
        ]);
    }

    public static function deletePlaylist($playlistId = null)
    {

        $playlist = self::whereId($playlistId)->first();

        if (!empty($playlistId)) {

            PlaylistLog::deleteMyPlaylist($playlistId);

            $playlist->delete();

            return true;

        } else {

            return false;

        }
    }

}
