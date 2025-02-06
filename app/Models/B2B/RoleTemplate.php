<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



use App\Events\DailyTip\NewDailyTip;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\Practitioner\PractitionerHelpers;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\Client\Plan\Plan;
use App\Models\User;
use Carbon\Carbon;


use App\Models\TipRecord;


class RoleTemplate extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }


    public static function allTemplate()
    {
        return self::whereNotNull('code')->orderBy('created_at', 'desc');
    }

    public static function updateIntentionPlan($data = null, $id = null)
    {
        if ($data['subscription_type'] == 'Freemium') {
            foreach ($data['code'] as $key => $code) {
                $data['code'] = $key;
                $data['min_point'] = $code['min'];
                $data['max_point'] = $code['max'];
            }
        } else {
            $codes = [];
            $min_points = [];
            $max_points = [];
            foreach ($data['code'] as $key => $code) {
                $codes[] = $key; // Add the key to the codes array
                $min_points[] = $code['min']; // Add min to the min_points array
                $max_points[] = $code['max']; // Add max to the max_points array
            }
            $data['code'] = implode(',', $codes);
            $data['min_point'] = implode(',', $min_points);
            $data['max_point'] = implode(',', $max_points);
        }

        $daily_tip = self::find($id);


        $daily_tip->update($data);

        return $daily_tip;
    }


    public static function createTemplate($data = null)
    {
        if ($data['subscription_type'] == 'Freemium') {
            foreach ($data['code'] as $key => $code) {
                $data['code'] = $key;
                $data['min_point'] = $code['min'];
                $data['max_point'] = $code['max'];
            }
        } else {
            $codes = [];
            $min_points = [];
            $max_points = [];
            foreach ($data['code'] as $key => $code) {
                $codes[] = $key; // Add the key to the codes array
                $min_points[] = $code['min']; // Add min to the min_points array
                $max_points[] = $code['max']; // Add max to the max_points array
            }
            $data['code'] = implode(',', $codes);
            $data['min_point'] = implode(',', $min_points);
            $data['max_point'] = implode(',', $max_points);
        }


        return self::create($data);
    }

    public static function deleteTemplate($id)
    {
        self::whereId($id)->delete();
    }
}
