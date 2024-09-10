<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Plan\Plan;
use Illuminate\Http\Request;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Admin\Coupon\Coupon;
use App\Models\Payment;
use App\Models\User;
use App\Models\Assessment;
use Illuminate\Support\Facades\DB;
use Stripe\BaseStripeClient;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\AssessmentColorCode;
use function PHPUnit\Framework\stringContains;

class PaymentController extends Controller
{

    public function showPaymentForm()
    {
        try {

            $user = Helpers::getWebUser();

            $assessmentCheck = Helpers::checkAssessment($user['id']);

            if ($assessmentCheck == 'free') {
                $assessment = Assessment::createAssessmentData($user['id'], 1);

                $assessment_data = Assessment::where('id', $assessment['id'])->first();

                AssessmentColorCode::createStylesCodeAndColor($assessment_data);

                AssessmentColorCode::createFeaturesCodeAndColor($assessment_data);

                return redirect()->route('test_play');

            }
            elseif ($assessmentCheck == 'play') {
                return redirect()->route('test_play');

            }
            else {
                $stripe_setting = StripeSetting::getSingle();

                $user = User::getSingleUser($user['id']);

                return view('client-dashboard.payment.index', compact('user', 'stripe_setting'));
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function processPayment(Request $request)
    {


        DB::beginTransaction();

        try {

            if ($request->has('plan_id') && !empty($request->input('plan_id'))){ // subscription case

                $key = StripeSetting::getSingle();

                $stripe = new StripeClient($key['api_key']);

                $user = Helpers::getWebUser();
//
                $user->createOrGetStripeCustomer();

                if (empty($user['payment_method'])){

//                    $payment_method_id = $stripe->paymentMethods->create([
//                        'type' => 'card',
//                        'card' => [
//                            'number' => $request->input('cardNumber'),
//                            'exp_month' => $request->input('expMonth'),
//                            'exp_year' => $request->input('expYear'),
//                            'cvc' => $request->input('cvc'),
//                        ],
//                    ]);

                    $payment_method = $stripe->paymentMethods->attach(
                        'pm_card_visa',
                        ['customer' => $user['stripe_id']]
                    );

                    User::updateUserPaymentMethod($payment_method);

                }

                if (strpos($request->input('cardNumber'), '************') === 0){

                    if ($user->subscriptions()->whereNull('deleted_at')->count() > 0){

                        $user->subscription('main')->swapAndInvoice($request->input('plan_id'));

                    }else{

                        $user->newSubscription('main' , $request->input('plan_id'))->create($user['payment_method'] ?? "");

                    }

                }else{

                    //                    $payment_method_id = $stripe->paymentMethods->create([
//                        'type' => 'card',
//                        'card' => [
//                            'number' => $request->input('cardNumber'),
//                            'exp_month' => $request->input('expMonth'),
//                            'exp_year' => $request->input('expYear'),
//                            'cvc' => $request->input('cvc'),
//                        ],
//                    ]);

                    $payment_method = $stripe->paymentMethods->attach(
                        'pm_card_visa',
                        ['customer' => $user['stripe_id']]
                    );

                    User::updateUserPaymentMethod($payment_method);

                    if ($user->subscriptions()->whereNull('deleted_at')->count() > 0){

                        $user->subscription('main')->swapAndInvoice($request->input('plan_id'));

                    }else{

                        $user->newSubscription('main' , $request->input('plan_id'))->create($payment_method !== null ? $payment_method->id : '');

                    }

                }

                DB::commit();

                return redirect()->route('client_dashboard')->with('success', 'Subscription successfully changed!');

            }else{ // Charge

                $user = Helpers::getWebUser();

                $key = StripeSetting::getSingle();
//
                Stripe::setApiKey($key['api_key']);

                $discount_amount = $request['amount'];

                $payment_method_id = $user->createOrGetStripeCustomer()->toArray()['invoice_settings']['default_payment_method'];

                $user->createOrGetStripeCustomer();

                if (!empty($user['pm_last_four'])) {

                    $user->charge($discount_amount * 100, $user['payment_method'] ?? $payment_method_id, [
                        'currency' => 'usd',
                        'description' => 'Test Payment',
                    ]);

                } else {

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

                $assessment = Assessment::createAssessmentData($user['id'], 0);

                $assessment_data = Assessment::where('id', $assessment['id'])->first();

                AssessmentColorCode::createStylesCodeAndColor($assessment_data);

                AssessmentColorCode::createFeaturesCodeAndColor($assessment_data);

                Payment::createPayment($coupon, $user['id'], $discount_amount, $stripe['amount'], $assessment['id']);

                DB::commit();

                return redirect()->route('test_play')->with('success', 'Payment successful!');

            }

        } catch (\Exception $exception) {

            DB::rollBack();

            return redirect()->back()->with(['error' => $exception->getMessage()]);
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
