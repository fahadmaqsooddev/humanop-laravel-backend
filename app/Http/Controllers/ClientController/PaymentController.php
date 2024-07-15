<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Admin\Coupon\Coupon;
use App\Models\Payment;
use App\Models\Assessment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends Controller
{

    public function showPaymentForm()
    {
        try {

            $stripe = StripeSetting::getSingle();

            return view('client-dashboard.payment.index', compact('stripe'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function processPayment(Request $request)
    {
        DB::beginTransaction();

        try {

            $user = Auth::user();

            $user->createOrGetStripeCustomer();

            $key = StripeSetting::getSingle();

            Stripe::setApiKey($key['api_key']);

            $discount_amount = $request['amount'];

            Charge::create([
                'amount' => $discount_amount*100, // Amount in cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Test Payment',
            ]);

            $coupon = Coupon::getSingleCoupon($request['coupon']);

            $stripe = StripeSetting::getSingle();

            $assessment = Assessment::createAssessmentData($user['id']);

            Payment::createPayment($coupon['id'], $user['id'], $discount_amount, $stripe['amount'], $assessment['id']);
            
            DB::commit();

            return redirect()->route('test_play')->with('success', 'Payment successful!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->route('stripe_checkout')->with('error', $e->getMessage());
        }
    }
}
