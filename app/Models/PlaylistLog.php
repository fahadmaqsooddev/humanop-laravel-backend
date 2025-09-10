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

        return self::create($playlist);
    }

    public static function deleteMyPlaylist($data = null)
    {
        if (empty($data['playlist_id']) || empty($data['user_id'])) {
            return false;
        }

        $query = self::where('playlist_id', $data['playlist_id'])
            ->where('user_id', $data['user_id']);

        if (!empty($data['playlist_resource_item_id'])) {
            $query->where('resource_item_id', $data['playlist_resource_item_id']);
        } elseif (!empty($data['playlist_shop_item_id'])) {
            $query->where('shop_item_id', $data['playlist_shop_item_id']);
        } elseif (!empty($data['playlist_podcast_item_id'])) {
            $query->where('podcast_id', $data['playlist_podcast_item_id']);
        } else {
            return false;
        }

        $playlist = $query->first();

        if ($playlist) {
            $playlist->delete();
            return true;
        }

        return false;
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
