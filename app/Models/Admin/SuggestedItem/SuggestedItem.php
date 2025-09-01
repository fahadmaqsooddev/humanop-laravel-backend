<?php

namespace App\Models\Admin\SuggestedItem;

use App\Helpers\Helpers;
use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestedItem extends Model
{
    use HasFactory;

    protected $appends = ['photo_url', 'video_url', 'audio_url'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    // append
    public function getPhotoUrlAttribute()
    {

        return Helpers::getImage($this->image_id);
    }

    public function getVideoUrlAttribute()
    {
        return Helpers::getVideo($this->video_id, 1, null);
    }

    public function getAudioUrlAttribute()
    {

        return Helpers::getAudio($this->audio_id, 1);
    }


    // relationship
    public function suggestedItems()
    {
        return $this->hasMany(HumanOpItemsGridActivitiesLog::class, 'suggested_item_id');
    }

    public function shopItems()
    {
        return $this->belongsTo(HumanOpItemsGridActivitiesLog::class, 'shop_item_id', 'id');
    }

    public function resourceItems()
    {
        return $this->belongsTo(HumanOpItemsGridActivitiesLog::class, 'resource_item_id', 'id');
    }

    public static function createSuggestedItem($module_type = null, $title = null, $description = null, $image_id = null, $video_id = null, $audio_id = null)
    {
        $resource = self::create([
            'title' => $title,
            'description' => $description,
            'module_type' => $module_type,
            'video_id' => $video_id,
            'audio_id' => $audio_id,
            'image_id' => $image_id,
        ]);

        return $resource;
    }

    public static function getSingleSuggestedItem($userId = null, $suggestions = null)
    {
        // Normalize suggestions to an array of IDs
        $excludeIds = [];

        if ($suggestions) {
            if ($suggestions instanceof \Illuminate\Support\Collection) {
                $excludeIds = $suggestions->pluck('suggested_item_id')->toArray();
            } elseif (is_array($suggestions)) {
                $excludeIds = array_column($suggestions, 'suggested_item_id');
            } elseif (isset($suggestions['suggested_item_id'])) {
                $excludeIds = [$suggestions['suggested_item_id']];
            }
        }

        // Start query from SuggestedItem model
        $query = self::query();

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->inRandomOrder()->first();
    }

    public static function getItem($suggestedItemId = null)
    {
        return self::where('id', $suggestedItemId)->first();
    }
}
