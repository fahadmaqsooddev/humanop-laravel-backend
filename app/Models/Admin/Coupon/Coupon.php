<?php

namespace App\Models\Admin\Coupon;

use App\Helpers\Helpers;
use App\Models\Admin\Coupon\CouponRedemption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Assessment;

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

    public static function getSingleCoupon($coupon = null)
    {
        return self::where('coupon', $coupon)->first();
    }

    public static function getCoupon()
    {
        return self::orderBy('created_at', 'desc');
    }

    public static function createDiscount($data = null)
    {

        $data['remaining_redemption'] = $data['limit'];
        $data['coupon'] = preg_replace('/[^A-Za-z]/', '', Str::random(9));

        return self::create($data);
    }

    public static function checkCouponCode($data = null, $original_amount = null)
    {
        // Find the coupon by code
        $coupon = self::where('coupon', $data['coupon'] ?? '')->first();

        // Check if the coupon exists
        if (empty($coupon)) {
            return ['error' => "Coupon Code Invalid", 'amount' => $original_amount];
        }

        // Check if the coupon has remaining redemptions
        if ($coupon['remaining_redemption'] === 0) {
            return ['error' => "Coupon has expired.", 'amount' => $original_amount];
        }

        // Check if the coupon has already been redeemed by the user
        if (CouponRedemption::checkRedemption($coupon['id'])) {
            return ['error' => "You have already used this coupon.", 'amount' => $original_amount];
        }

        // Calculate discount amount
        $discount_amount = $original_amount - ($coupon['discount'] / 100 * $original_amount);
        $discount_amount = (int) $discount_amount;

        // Handle unlimited coupon usage
        if (is_null($coupon['limit']) && $coupon['discount'] == 100) {
            Assessment::createAssessmentData(Helpers::getWebUser()->id);
            return [
                'success' => "Congratulations! You've Won a Special Discount",
                'status' => 202
            ];
        }

        // Handle 100% discount with limited usage
        if ($coupon['discount'] == 100) {
            Assessment::createAssessmentData(Helpers::getWebUser()->id);
            CouponRedemption::createRedemption($coupon['id']);

            // Decrement remaining redemptions if there's a limit
            if ($coupon['limit'] !== null && $coupon['limit'] > 0) {
                $coupon->decrement('remaining_redemption');
            }

            return [
                'success' => "Congratulations! You've Won a Special Discount",
                'status' => 202
            ];
        }

        // Handle general limited coupon usage
        if ($coupon['limit'] !== null) {
            CouponRedemption::createRedemption($coupon['id']);

            // Decrement remaining redemptions if there's a limit
            if ($coupon['limit'] > 0) {
                $coupon->decrement('remaining_redemption');
            }

            return [
                'success' => "Congratulations! You've Won a Special Discount",
                'status' => 200,
                'amount' => $discount_amount
            ];
        }

        // If no special case was matched, return the discounted amount
        return [
            'success' => "Congratulations! You've Won a Special Discount",
            'status' => 200,
            'amount' => $discount_amount
        ];
    }

    public static function redeemCouponCodeForApi($code = null, $original_amount = null){

        $coupon = self::where('coupon', $code)->first();

        if ($coupon){

            if ($coupon['remaining_redemption'] != 0){

                return CouponRedemption::checkOrCreateCouponRedemption($coupon, $original_amount);

            }else{

                $message = "Coupon has expired.";
            }

        }else{

            $message = "Coupon Code Invalid";
        }

        return Helpers::validationResponse($message);

    }

}
