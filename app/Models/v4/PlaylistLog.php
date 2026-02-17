<?php

namespace App\Models\v4;

use App\Helpers\ActivityLogs\ActivityLogger;
use App\Models\Admin\MediaPlayer\MediaPlayerResources;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\ShopCategoryResource;
use App\Models\Playlist\Playlist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistLog extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function resourceItems()
    {
        return $this->hasMany(LibraryResource::class, 'id', 'resource_item_id');
    }

    public function shopItems()
    {
        return $this->hasMany(ShopCategoryResource::class, 'id', 'shop_item_id');
    }

    public function podcastItems()
    {
        return $this->hasMany(Podcast::class, 'id', 'podcast_id');
    }

    public function mediaPlayerItems()
    {
        return $this->hasMany(MediaPlayerResources::class, 'id', 'media_player_item_id');
    }

    public static function addMyPlaylist($playlist = null)
    {

        $latestPlaylistItem = self::where('user_id', $playlist['user_id'])->latest()->first();

        if ($latestPlaylistItem) {

            $playlist['order'] = 1 + $latestPlaylistItem['order'];

        } else {

            $playlist['order'] = 1;

        }

        $playlistName = Playlist::getSinglePlaylist($playlist['playlist_id'])['title'];

        if (!empty($playlist['resource_item_id'])) {

            $library = LibraryResource::singleLibraryResource($playlist['resource_item_id']);

            ActivityLogger::addLog('Playlist', "You have added the '{$library->heading}' Tool & Training to the '{$playlistName}' playlist.");

        }elseif (!empty($playlist['shop_item_id'])){

            $shop = ShopCategoryResource::singleLibraryResource($playlist['shop_item_id']);

            ActivityLogger::addLog('Playlist', "You have added the '{$shop->heading}' HumanOp Shop Item to the '{$playlistName}' playlist.");

        }elseif (!empty($playlist['podcast_id'])){

            $podcast = Podcast::singlePodcast($playlist['podcast_id']);

            ActivityLogger::addLog('Playlist', "You have added the '{$podcast->title}' Podcast to the '{$playlistName}' playlist.");

        }else{

            $media = MediaPlayerResources::singleLibraryResource($playlist['media_player_item_id']);

            ActivityLogger::addLog('Playlist', "You have added the '{$media->heading}' Media Player to the '{$playlistName}' playlist.");

        }


        return self::create($playlist);
    }

    public static function sortingMyPlaylist($playlist = null)
    {

        foreach ($playlist['playlist_item_ids'] as $key => $itemId) {

            $playlistItem = self::where('user_id', $playlist['user_id'])->where('id', $itemId)->first();

            $playlistItem->order = $key + 1;

            $playlistItem->save();

        }

    }

    public static function deleteMyPlaylist($data = null)
    {
        if (empty($data['playlist_id']) || empty($data['user_id'])) {

            return false;

        }

        $playlistItem = self::where('id', $data['playlist_item_id'])
            ->where('playlist_id', $data['playlist_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if (!empty($playlistItem)) {

            $playlistName = Playlist::getSinglePlaylist($data['playlist_id'])['title'];

            if (!empty($playlistItem->resource_item_id)) {

                $library = LibraryResource::singleLibraryResource($playlistItem['resource_item_id']);

                ActivityLogger::addLog('Playlist', "You have deleted the '{$library->heading}' Media Player to the '{$playlistName}' playlist.");

            }elseif (!empty($playlistItem->shop_item_id)){

                $shop = ShopCategoryResource::singleLibraryResource($playlistItem['shop_item_id']);

                ActivityLogger::addLog('Playlist', "You have deleted the '{$shop->heading}' HumanOp Shop Item to the '{$playlistName}' playlist.");

            }elseif (!empty($playlistItem->podcast_id)){

                $podcast = Podcast::singlePodcast($playlistItem['podcast_id']);

                ActivityLogger::addLog('Playlist', "You have deleted the '{$podcast->title}' Podcast to the '{$playlistName}' playlist.");

            }else{

                $media = MediaPlayerResources::singleLibraryResource($playlistItem['media_player_item_id']);

                ActivityLogger::addLog('Playlist', "You have deleted the '{$media->heading}' Media Player to the '{$playlistName}' playlist.");

            }

            $playlistItem->delete();

            return true;

        } else {

            return false;

        }

    }

    public static function getSingleResourceItem($resourceItemId = null)
    {
        return self::where('resource_item_id', $resourceItemId)->first();

    }

    public static function getSingleShopItem($shopItemId = null)
    {
        return self::where('shop_item_id', $shopItemId)->first();

    }

    public static function getSinglePodcastItem($podcastItemId = null)
    {
        return self::where('podcast_id', $podcastItemId)->first();

    }

}
