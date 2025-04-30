<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2BCoupon extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function allCoupons()
    {
        return self::all();
    }
    public static function createB2BCoupon($couponName = null, $couponId = null, $couponDuration = null, $couponLimit = null)
    {
        return self::create([
            'coupon_name' => $couponName,
            'coupon_code' => $couponId,
            'coupon_limit' => $couponLimit,
            'coupon_duration' => $couponDuration,
        ]);
    }

    public static function deleteCoupon($couponId = null)
    {
        return self::where('coupon_code', $couponId)->delete();
    }
}
