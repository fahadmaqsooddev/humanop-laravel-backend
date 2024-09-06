<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\CheckoutPaymentRequest;
use App\Http\Requests\Api\Client\RedeemCouponRequest;
use App\Models\Admin\Coupon\Coupon;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\BaseStripeClient;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function paymentCheckout(CheckoutPaymentRequest $request){

        DB::beginTransaction();

        try {

            $user = Helpers::getUser();

            $user->createOrGetStripeCustomer();

            $stripe = StripeSetting::getSingle();

            if ($stripe){

                $stripe_client = new StripeClient($stripe['api_key']);

                if (Str::contains($request->input('card_number'),'*') && $user['payment_method']){

                    $user->charge($request->input('price') * 100, $user['payment_method'], [
                        'currency' => 'usd',
                        'description' => 'Test Payment',
                    ]);

                }else{

                    $payment_method = $stripe_client->paymentMethods->attach(
                        'pm_card_visa',
                        ['customer' => $user['stripe_id']]
                    );

                    $user->charge($request->input('price') * 100, $payment_method['id'], [
                        'currency' => 'usd',
                        'description' => 'Test Payment',
                    ]);

                    User::updateUserPaymentMethodFromApi($payment_method, $request);

                }

                $assessment = Assessment::createAssessmentData($user['id'], 0);

                $assessment_data = Assessment::where('id', $assessment['id'])->first();

                AssessmentColorCode::createStylesCodeAndColor($assessment_data);

                AssessmentColorCode::createFeaturesCodeAndColor($assessment_data);

                $coupon = Coupon::getSingleCoupon($request['coupon']);

                $data['user_id'] = $user['id'];
                $data['coupon_id'] =  $coupon->id ?? null;
                $data['assessment_id'] = $assessment->id ?? null;
                $data['discount_price'] = $request['price'];
                $data['total_price'] = $stripe['amount'];

                Payment::createPaymentFromApi($data);

                DB::commit();

                return Helpers::successResponse('Payment successfully charged');
            }

            DB::rollBack();

            return Helpers::validationResponse('Something went wrong while charging');

        }catch (\Exception $exception){

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function redeemCoupon(RedeemCouponRequest $request){

        try {

            $original_amount = StripeSetting::getSingle();

            $response = Coupon::redeemCouponCodeForApi($request->input('coupon_code'), $original_amount['amount']);

            return $response;

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function paymentHistory(Request $request){

        try {

            $user_id = Helpers::getUser()->id;

            $payments = Payment::paginatedPaymentHistory($request, $user_id);

            return Helpers::successResponse('Payment History', $payments, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public static function billing(){

        try {

            $user = Helpers::getUser();

            $data = [
                'last_four_digits' => $user['pm_last_four'],
                'exp_month' => $user['pm_exp_month'],
                'exp_year' => $user['pm_exp_year'],
                'name' => $user['card_name'],
                'last_used' => $user->payments()->latest()->first()->created_at ?? null,
            ];

            return Helpers::successResponse('Billing information', $data);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
