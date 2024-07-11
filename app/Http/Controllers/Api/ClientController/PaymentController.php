<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\CheckoutPaymentRequest;
use App\Http\Requests\Api\Client\RedeemCouponRequest;
use App\Models\Admin\Coupon\Coupon;
use App\Models\Admin\StripeSetting\StripeSetting;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function paymentCheckout(CheckoutPaymentRequest $request){

        try {

            $user = Helpers::getUser();

            $user->createOrGetStripeCustomer();

            $key = StripeSetting::getSingle();

            if ($key){

                Stripe::setApiKey($key['api_key']);

                Charge::create([
                    'amount' => ($request['amount'] * 100), // Amount in cents
                    'currency' => 'usd',
                    'source' => $request->input('stripe_token'),
                    'description' => 'Test Payment',
                ]);

                return Helpers::successResponse('Payment successfully charged');
            }

            return Helpers::validationResponse('Something went wrong while charging');

        }catch (\Exception $exception){

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
}
