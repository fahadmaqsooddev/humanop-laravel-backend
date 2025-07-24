<?php

namespace App\Models\Admin\AnnouncementNews;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementNews extends Model
{
    use HasFactory;


    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getSingleAnnouncementNews($id = null)
    {
        return self::whereId($id)->first();
    }
    public static function getAnnouncementNews()
    {
        return self::orderBy('updated_at', 'desc')->get();
    }

    public static function createAnnouncementNews($data = null)
    {

        return self::create($data);
    }

    public static function updateAnnouncementNews($announcementNewsId = null, $announcementNewsTitle = null, $announcementNewsDescription = null)
    {

        $announcementNews = self::getSingleAnnouncementNews($announcementNewsId);
        $announcementNews->title = $announcementNewsTitle;
        $announcementNews->description = $announcementNewsDescription;
        $announcementNews->save();

        return $announcementNews;
    }

    public static function deleteAnnouncementNews($announcementNewsId = null)
    {

        $announcementNews = self::getSingleAnnouncementNews($announcementNewsId);
        $announcementNews->delete();

        return $announcementNews;
    }

}
