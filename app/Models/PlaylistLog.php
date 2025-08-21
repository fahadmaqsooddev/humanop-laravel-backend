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

    public static function deleteMyPlaylist($playlistId = null)
    {
        $playlist = self::whereId($playlistId)->first();

        if (!empty($playlist)) {

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
