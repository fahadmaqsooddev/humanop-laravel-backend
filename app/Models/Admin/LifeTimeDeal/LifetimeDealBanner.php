<?php

namespace App\Models\Admin\LifeTimeDeal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifetimeDealBanner extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getSingleBanner($bannerId = null)
    {
        return self::where('id', $bannerId)->first();
    }

    public static function createOrUpdateBanner($bannerId = null, $banner = [])
    {

        if (empty($banner) || !is_array($banner)) {
            return false;
        }

        $getBanner = self::getSingleBanner($bannerId);

        if ($getBanner) {

            $getBanner->update($banner);

        } else {

            self::create($banner);

        }

        return true;

    }



}
