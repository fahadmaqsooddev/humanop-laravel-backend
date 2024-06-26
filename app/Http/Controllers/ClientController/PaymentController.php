<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Admin\Coupon\Coupon;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends Controller
{

    public function showPaymentForm()
    {
        try {

            $stripe = StripeSetting::getSingle();
            $coupon = Coupon::getSingle();

            return view('client-dashboard.payment.index', compact('stripe', 'coupon'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function processPayment(Request $request)
    {
        try {

            $user = Auth::user();

            $user->createOrGetStripeCustomer();

            $key = StripeSetting::getSingle();

            Stripe::setApiKey($key['api_key']);

            Charge::create([
                'amount' => $request['amount']*100, // Amount in cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Test Payment',
            ]);

        return redirect()->route('test_play')->with('success', 'Payment successful!');

        } catch (\Exception $e) {

            return redirect()->route('stripe_checkout')->with('error', $e->getMessage());
        }
    }
}
