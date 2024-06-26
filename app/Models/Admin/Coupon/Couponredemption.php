<?php

namespace App\Models\Admin\Coupon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Couponredemption extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getRedemption()
    {
        return self::where('user_id', Auth::user()['id'])->pluck('coupon_id');
    }

    public static function checkRedemption($coupon_id)
    {
        $coupon = self::where('user_id', Auth::user()->id)->where('coupon_id', $coupon_id)->first();

        if (!empty($coupon))
        {
            return true;

        } else {

            return false;
        }
    }


    public static function createRedemption($data = null)
    {
        $coupon['coupon_id'] = $data;
        $coupon['user_id'] = Auth::user()['id'];

        return self::create($coupon);

    }
}
