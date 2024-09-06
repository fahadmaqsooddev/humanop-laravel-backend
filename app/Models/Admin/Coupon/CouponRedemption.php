<?php

namespace App\Models\Admin\Coupon;

use App\Helpers\Helpers;
use App\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CouponRedemption extends Model
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

    public static function checkOrCreateCouponRedemption($coupon = null, $original_amount = null){

        $coupon_found = CouponRedemption::where([['coupon_id', $coupon->id],['user_id', Helpers::getUser()->id]])->exists();

        if ($coupon['discount'] == 100){
            Assessment::createAssessmentData(Helpers::getUser()->id);
        }

        if (!$coupon_found){

            if ($coupon['limit'] == null){
                // if coupon limit is null so user use it unlimited
            }else{

                self::create(['coupon_id' => $coupon->id, 'user_id' => Helpers::getUser()->id]);

                if ($coupon['limit'] > 0) {

                    $coupon->decrement('remaining_redemption');

                }

            }

            $dis_amount = (int)$original_amount - ((int)$coupon['discount'] / 100 * (int)$original_amount);

            $data = ['amount' => (int)$dis_amount];

            return Helpers::successResponse("Congratulations! You've Won a Special Discount", $data);

        }elseif ($coupon['limit'] == null){

            $dis_amount = (int)$original_amount - ((int)$coupon['discount'] / 100 * (int)$original_amount);

            $data = ['amount' => (int)$dis_amount];

            return Helpers::successResponse("Congratulations! You've Won a Special Discount", $data);

        }else{

            return Helpers::validationResponse("You have already used this coupon.");

        }

    }
}
