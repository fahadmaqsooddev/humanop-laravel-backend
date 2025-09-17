<?php

namespace App\Models\Playlist;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistItemTrack extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getPlaylistItem($playlist_id = null, $item_id = null, $user_id = null)
    {

        return self::where('playlist_id', $playlist_id)->where('user_id', $user_id)->where('item_id', $item_id)->first();

    }

    public static function checkPlaylistItem($data = null)
    {
        return self::where('playlist_id', $data['playlist_id'])->where('user_id', $data['user_id'])->where('item_id', $data['item_id'])->first();
    }

    public static function createPlaylistItem($data = null)
    {
        return self::create($data);
    }

    public static function checkOrUpdatePlaylistItem($data = null)
    {

        if (empty($data)) {

            return false;

        }

        $item = self::checkPlaylistItem($data);

        if (!empty($item)){

            $item->update($data);

            return true;

        }else{

            self::createPlaylistItem($data);

            return true;
        }

    }

}
