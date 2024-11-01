<?php

namespace App\Models\Information;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getCoreStatsInfo()
    {
        return self::where('name', 'Core Stats')->first(['name', 'information']);
    }

    public static function getActionPlanInfo()
    {
        return self::where('name', '90 Day Strategy')->first(['name', 'information']);
    }

    public static function getDailyTipInfo()
    {
        return self::where('name', 'Daily Tips')->first(['name', 'information']);
    }

    public static function getLibraryResourceInfo()
    {
        return self::where('name', 'Library of Resources')->first(['name', 'information']);
    }

    public static function getHelpInfo()
    {
        return self::where('name', "HELP! I’M HAVING A CHALLENGE…")->first(['name', 'information']);
    }

    public static function getProfileOverviewInfo()
    {
        return self::where('name', "Your HumanOp Profile Overview")->first(['name', 'information']);
    }

    public static function getHaiChatInfo()
    {
        return self::where('name', "HAi Chat")->first(['name', 'information']);
    }
}
