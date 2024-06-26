<?php

namespace App\Models\Admin\Coupon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Admin\Coupon\CouponRedemption;

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

    public static function getCoupon()
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    public static function createDiscount($data = null)
    {

        $data['remaining_redemption'] = $data['limit'];
        $data['coupon'] = preg_replace('/[^A-Za-z]/', '', Str::random(9));

        return self::create($data);
    }

    public static function checkCouponCode($data = null, $original_amount = null)
    {
        $coupon = self::where('coupon', $data['coupon'])->first();

        if (empty($coupon))
        {
            $data = ['error' => "Coupon Code Invalid", 'amount' => $original_amount];
        }
        else
        {
            $checkCouponRedemption = CouponRedemption::checkRedemption($coupon['id']);

            if ($checkCouponRedemption == true)
            {
                $data = ['error' => "You have already used this coupon.", 'amount' => $original_amount];
            }

            else
            {
                if ($coupon && $coupon['discount'] == 100)
                {
                    CouponRedemption::createRedemption($coupon['id']);

                    $data = ['status' => 202];
                }
                else
                {

                    if ($coupon && $coupon['limit'] == 0) {

                        $dis_amount = $original_amount - ($coupon['discount'] / 100 * $original_amount);

                        $dis_amount = (int) $dis_amount;

                        CouponRedemption::createRedemption($coupon['id']);

                        $data = ['success' => "Congratulations! You've Won a Special Discount", 'status' => 200, 'amount' => $dis_amount];

                    }
                    elseif ($coupon && $coupon['limit'] > 0)
                    {
                        $dis_amount = $original_amount - ($coupon->discount / 100 * $original_amount);

                        $dis_amount = (int) $dis_amount;

                        $coupon['remaining_redemption'] -= 1;

                        $coupon->update();

                        CouponRedemption::createRedemption($coupon['id']);

                        $data = ['success' => "Congratulations! You've Won a Special Discount", 'status' => 200, 'amount' => $dis_amount];

                    }
                }
            }
        }

        return $data;
    }
}
