<?php

namespace App\Models\Admin\MediaPlayer;

use App\Helpers\Helpers;
use App\Models\Libraries\HumanOpLibraries;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MediaPlayerResources extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    protected $appends = ['video_url', 'audio_url', 'thumbnail_url'];

    public function getThumbnailUrlAttribute()
    {
        if (!empty($this->thumbnail_id)) {

            return Helpers::getImage($this->thumbnail_id);

        } else {

            return null;
        }

    }

    public function getVideoUrlAttribute()
    {

        if (!empty($this->video_id)){

            return Helpers::getVideo($this->video_id, 1, null);

        }else{

            return null;

        }

    }

    public function getAudioUrlAttribute()
    {

        if (!empty($this->audio_id)){

            return Helpers::getMp3Url($this->audio_id);

        }else{

            return null;

        }

    }

    public function resourceCategory()
    {
        return $this->belongsTo(MediaPlayerCategories::class, 'media_player_category_id', 'id');
    }

    public static function getMediaPlayResources($mediaPlayerId = null)
    {

        return self::where('media_player_category_id', $mediaPlayerId)->with('resourceCategory')->get();
    }

    public static function deleteResourceOfCategory($id = null)
    {
        return self::where('media_player_category_id', $id)->delete();
    }

    public static function singleLibraryResource($resource_id)
    {
        return self::whereId($resource_id)->first();
    }

    public static function updateCategory($current, $new)
    {
        self::where('media_player_category_id', $current)->update(['media_player_category_id' => $new]);
    }

    public static function createResource($heading = null, $category_id = null, $description = null, $thumbnailId = null, $embed_link = null, $audioId = null)
    {
        $resource = self::create([
            'heading' => $heading,
            'slug' => Str::slug($heading),
            'media_player_category_id' => $category_id,
            'description' => $description,
            'thumbnail_id' => $thumbnailId,
            'video_embed_link' => $embed_link,
            'audio_id' => $audioId
        ]);

        return $resource;
    }

    public static function updateResource($id = null, $heading = null, $category_id = null, $description = null)
    {

        self::whereId($id)->update([
            'heading' => $heading,
            'slug' => Str::slug($heading),
            'media_player_category_id' => $category_id,
            'description' => $description,
        ]);

        return self::singleLibraryResource($id);

    }

    public static function deleteResource($id = null)
    {
        return self::whereId($id)->delete();
    }

    public static function resourceCategoriesForClient()
    {
        $user = Helpers::getUser();

        $userId = $user['id'];
        $userPlan = $user['plan_name'];

        $purchasedItemIds = HumanOpLibraries::getAllLibraries($userId)
            ->pluck('library_resource_id')
            ->toArray();

        $query = self::query()
            ->whereNotIn('id', $purchasedItemIds);

        $allowedPermissions = match ($userPlan) {
            'Premium' => [2, 1],
            default => [1],
        };

        $query->whereIn('permission', $allowedPermissions)->with(['resourceCategory'])->orderBy('created_at', 'desc');

        return $query->get();
    }


}
