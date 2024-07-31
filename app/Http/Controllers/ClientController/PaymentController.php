<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Admin\Coupon\Coupon;
use App\Models\Payment;
use App\Models\User;
use App\Models\Assessment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\BaseStripeClient;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\AssessmentColorCode;

class PaymentController extends Controller
{

    public function showPaymentForm()
    {
        try {

            $stripe_setting = StripeSetting::getSingle();

            $user = User::getSingleUser(Auth::user()['id']);

            return view('client-dashboard.payment.index', compact('user', 'stripe_setting'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function processPayment(Request $request)
    {
        DB::beginTransaction();

        try {

            $user = Auth::user();

            $key = StripeSetting::getSingle();

            Stripe::setApiKey($key['api_key']);

            $discount_amount = $request['amount'];

            $user->createOrGetStripeCustomer();

            if (!empty($user['pm_last_four']))
            {

                $user->charge($discount_amount * 100, $user['payment_method'], [
                    'currency' => 'usd',
                    'description' => 'Test Payment',
                ]);

            }else
            {
                Charge::create([
                    'amount' => $discount_amount * 100, // Amount in cents
                    'currency' => 'usd',
                    'source' => $request->stripeToken,
                    'description' => 'Test Payment',
                ]);
            }

            $stripe = new StripeClient($key['api_key']);

            $payment_method = $stripe->paymentMethods->attach(
                'pm_card_visa',
                ['customer' => $user['stripe_id']]
            );

            User::updateUserPaymentMethod($payment_method);

            $coupon = Coupon::getSingleCoupon($request['coupon']);

            $stripe = StripeSetting::getSingle();

            $assessment = Assessment::createAssessmentData($user['id']);
            
            $assessment_data = Assessment::where('id', $assessment['id'])->first();

            AssessmentColorCode::createStylesCodeAndColor($assessment_data);

            AssessmentColorCode::createFeaturesCodeAndColor($assessment_data);

            Payment::createPayment($coupon, $user['id'], $discount_amount, $stripe['amount'], $assessment['id']);

            DB::commit();

            return redirect()->route('test_play')->with('success', 'Payment successful!');

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function PaymentHistory()
    {
        try {

            $payment_history = Payment::getPaymentHistory();

            return view('client-dashboard.payment.payment_history', compact('payment_history'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }
}
