<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Coupon\Coupon;

class CouponController extends Controller
{

    protected $coupon = null;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function allCoupon()
    {
        try {

            $coupons = Coupon::getCoupon();

            return view('admin-dashboards.coupons.index', compact('coupons'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
