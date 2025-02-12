<?php

namespace App\Models\Information;

use App\Enums\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Enum;

class InformationIcon extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getInfo()
    {
        return self::select(['id','name','information'])->get();
    }

    public static function createInfo($name = null, $information = null)
    {
        $infoDetail = self::create([
            'name' => $name,
            'information' => $information,
        ]);

        return $infoDetail;
    }

    public static function editInfo($id = null, $name = null, $information = null)
    {

        $infoDetail = self::whereId($id)->update([
            'name'=> $name,
            'information'=> $information,
        ]);

        return $infoDetail;
    }

    // public static function getCoreStatsInfo()
    // {
    //     return self::where('id', Admin::CORE_STATS_INFO)->first(['name', 'information']);
    // }

    // public static function getActionPlanInfo()
    // {
    //     return self::where('id', Admin::ACTION_PLAN_INFO)->first(['name', 'information']);
    // }

    // public static function getDailyTipInfo()
    // {
    //     return self::where('id', Admin::DAILY_TIP_INFO)->first(['name', 'information']);
    // }

    // public static function getPodcastInfo()
    // {
    //     return self::where('id', Admin::INTEGRATION_PODCAST_INFO)->first(['name', 'information']);
    // }

    // public static function getLibraryResourceInfo()
    // {
    //     return self::where('id', Admin::RESOURCE_INFO)->first(['name', 'information']);
    // }

    // public static function getHelpInfo()
    // {
    //     return self::where('id', Admin::CHALLENGE_BUTTON_INFO)->first(['name', 'information']);
    // }

    // public static function getProfileOverviewInfo()
    // {
    //     return self::where('id', Admin::PROFILE_OVERVIEW_INFO)->first(['name', 'information']);
    // }

    // public static function getHaiChatInfo()
    // {
    //     return self::where('id', Admin::HAI_CHAT_INFO)->first(['name', 'information']);
    // }
}
