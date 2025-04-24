<?php

namespace App\Http\Livewire\B2b\B2bCoupon;

use Livewire\Component;
use Stripe\Coupon;
use Stripe\Stripe;

class ListCoupon extends Component
{

    protected $coupons;
    public function getCoupon()
    {
        Stripe::setApiKey(config('cashier.secret')); // or env('STRIPE_SECRET')

        $this->coupons = Coupon::all();

    }

    public function render()
    {
        $this->getCoupon();

        return view('livewire.b2b.b2b-coupon.list-coupon', [
            'coupons' => $this->coupons,
        ]);
    }
}
