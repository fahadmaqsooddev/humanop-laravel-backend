<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\GenerateCouponRequest;
use App\Models\Admin\Coupon\Coupon;
use App\Models\LifetimeCoupon;
use Illuminate\Support\Str;

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

    public function manageCoupons()
    {
        try {
            $coupons = LifetimeCoupon::with('redeemedBy')
                ->orderBy('created_at', 'desc')
                ->paginate(50);

            $stats = [
                'total' => LifetimeCoupon::count(),
                'premium_lifetime' => LifetimeCoupon::where('type', 'premium_lifetime')->count(),
                'beta_breaker_club' => LifetimeCoupon::where('type', 'beta_breaker_club')->count(),
                'redeemed' => LifetimeCoupon::where('is_redeemed', true)->count(),
                'available' => LifetimeCoupon::where('is_redeemed', false)->count(),
            ];

            return view('admin-dashboards.lifetime-coupons.index', compact('coupons', 'stats'));

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function generateLifetimeCoupons(GenerateCouponRequest $request)
    {
        try {
            $codes = [];

            for ($i = 0; $i < $request['quantity']; $i++) {
                $codes[] = LifetimeCoupon::create([
                    'type' => $request['type'],
                    'code' => strtoupper(Str::random(8)),
                ]);
            }

            return response()->json([
                'status' => true,
                'type' => $request['type'],
                'codes' => collect($codes)->pluck('code'),
            ]);

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
