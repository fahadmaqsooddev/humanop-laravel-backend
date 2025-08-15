<?php

namespace App\Models\Admin\SuggestedItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestedItem extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function createSuggestedItem($title = null, $description = null, $image_id = null, $video_id = null, $audio_id = null)
    {
        $resource = self::create([
            'title' => $title,
            'description' => $description,
            'video_id' => $video_id,
            'audio_id' => $audio_id,
            'image_id' => $image_id,
        ]);

        return $resource;
    }

}
