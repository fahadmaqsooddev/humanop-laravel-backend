<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\CheckoutPaymentRequest;
use App\Http\Requests\Api\Client\RedeemCouponRequest;
use App\Models\Admin\Coupon\Coupon;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Assessment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Stripe;

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

                Stripe::setApiKey($stripe['api_key']);

                Charge::create([
                    'amount' => ($request['price'] * 100), // Amount in cents
                    'currency' => 'usd',
                    'source' => $request->input('stripe_token'),
                    'description' => 'Test Payment',
                ]);

                $assessment = Assessment::createAssessmentData($user['id']);

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
}
