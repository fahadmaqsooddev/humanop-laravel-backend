<?php

namespace App\Models\Admin\RecentActivity;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }


    public static function createAccountActivity($businessId = null, $message = null, $type = null)
    {

        return self::create([
            'business_id' => $businessId,
            'message' => $message,
            'type' => $type == 1 ? Admin::IS_TEAM_MEMBER : Admin::IS_CANDIDATE,
        ]);
    }

    public static function sharedOrNotSharedDataActivity($businessId = null, $message = null, $type = null)
    {

        return self::create([
            'business_id' => $businessId,
            'message' => $message,
            'type' => $type == 1 ? Admin::IS_CANDIDATE : Admin::IS_TEAM_MEMBER,
        ]);
    }

}
