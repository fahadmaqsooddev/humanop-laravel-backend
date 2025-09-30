<?php

namespace App\Models\Admin\MediaPlayer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaPlayerCategories extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    // relations
    public function MediaPlayerResources()
    {

        return $this->hasMany(MediaPlayerResources::class, 'media_player_category_id', 'id')->whereNotNull('media_player_category_id');
    }

    public static function createCategory($name)
    {
        self::create(['name' => $name]);
    }

    public static function dropDownCategories()
    {
        return self::all();
    }

    public static function categories()
    {
        return self::with('MediaPlayerResources')->get();
    }

    public static function deleteSingleCategory($id)
    {
        MediaPlayerResources::deleteResourceOfCategory($id);

        self::whereId($id)->delete();
    }


}
