<?php

namespace App\Models;

use App\Models\Admin\Podcast\Podcast;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\ShopCategoryResource;
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

    public static function addMyPlaylist($playlist = null)
    {

        $latestPlaylistItem = self::where('user_id', $playlist['user_id'])->latest()->first();

        if ($latestPlaylistItem) {

            $playlist['order'] = 1 + $latestPlaylistItem['order'];

        } else {

            $playlist['order'] = 1;

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
