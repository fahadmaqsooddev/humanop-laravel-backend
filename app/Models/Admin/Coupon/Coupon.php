<?php

namespace App\Models\Admin\Coupon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getSingle()
    {
        return self::first();
    }

    public static function checkCouponDiscount()
    {
        $coupon = self::where('discount', 100)->where('limit', '>', 0)->first();

        if (!empty($coupon))
        {
            $coupon->limit -= 1;

            $coupon->save();

            return 1;

        }else
        {
            return 0;
        }
    }

    public static function updateDiscount($data = null, $id = null)
    {
        $discount = self::whereId($id)->first();

        $discount->update($data);

        return $discount;
    }

    public static function checkCouponCode($data = null, $original_amount = null)
    {
        $coupon = self::where('coupon', $data['coupon'])->first();

        if ($coupon && $coupon->limit > 0) {

            $dis_amount = $original_amount - ($coupon->discount / 100 * $original_amount);

            $dis_amount = (int) $dis_amount;

            $coupon->limit -= 1;

            $coupon->save();

            $data = ['success' => "Congratulations! You've Won a Special Discount Coupon", 'status' => 200, 'amount' => $dis_amount];

        } else {

            $data = ['error' => "Coupon Code Invalid", 'amount' => $original_amount];

        }

        return $data;
    }
}
