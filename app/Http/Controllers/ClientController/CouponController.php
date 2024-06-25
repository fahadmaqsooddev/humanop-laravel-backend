<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Coupon\CheckCouponRequest;
use App\Models\Admin\Coupon\Coupon;
use App\Models\Admin\StripeSetting\StripeSetting;

class CouponController extends Controller
{

    protected $coupon = null;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function checkCoupon(CheckCouponRequest $request)
    {
        try {

            $dataArray = $request->only($this->coupon->getFillable());

            $original_amount = StripeSetting::getSingle();

            $amount = Coupon::checkCouponCode($dataArray, $original_amount['amount']);

            return $amount;

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
