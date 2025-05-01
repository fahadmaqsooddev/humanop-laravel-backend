<?php

namespace App\Http\Livewire\B2b\B2bCoupon;

use App\Helpers\Helpers;
use App\Models\B2B\B2BCoupon;
use Livewire\Component;
use Stripe\Coupon;
use Stripe\Stripe;

class ListCoupon extends Component
{

    protected $coupons;
    protected $listeners = ['refreshB2BCoupon' => 'handleRefreshCoupon','deleteCoupon'];

    public function getCoupon()
    {
        Stripe::setApiKey(config('cashier.secret')); // or env('STRIPE_SECRET')

        $this->coupons = B2BCoupon::allCoupons();

    }

    public function deleteCoupon($coupon_id)
    {
        Stripe::setApiKey(config('cashier.secret')); // or env('STRIPE_SECRET')

        try {

            $coupon = Coupon::retrieve($coupon_id);

            $coupon->delete();

            B2BCoupon::deleteCoupon($coupon_id);

            session()->flash('success', 'Coupon deleted');


        } catch (\Stripe\Exception\InvalidRequestException $e) {

            session()->flash('error', 'Something went wrong: ' . $e->getMessage());

            return Helpers::serverErrorResponse($e->getMessage());

        }
    }

    public function handleRefreshCoupon(){

        $this->getCoupon();
    }

    public function render()
    {
        $this->getCoupon();

        return view('livewire.b2b.b2b-coupon.list-coupon', [
            'coupons' => $this->coupons,
        ]);
    }
}
