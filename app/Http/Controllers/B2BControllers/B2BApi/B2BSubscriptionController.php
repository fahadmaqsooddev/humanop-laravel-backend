<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BillingInfo\BillingInfo;
use App\Models\Client\Plan\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Coupon;
use Stripe\Exception\CardException;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class B2BSubscriptionController extends Controller
{

    protected $auth;

    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth:api');

        $this->auth = Auth::guard('api');

        $this->user = $user;
    }

    public function pricingPlans()
    {
        try {

            Stripe::setApiKey(config('cashier.secret'));

            $prices = Plan::getB2BPlans();

            $plans = [];

            foreach ($prices as $getPrice) {
                // Step 1: Get the Price
                $price = Price::retrieve($getPrice['plan_id']);

                // Step 2: Get the associated Product
                $product = Product::retrieve($price->product);

                $plans[] = [
                    'price_id' => $price->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_amount' => $price->unit_amount,
                    'interval' => $price->recurring->interval ?? null,
                    'no_of_team_members' => $getPrice['no_of_team_members'],
                ];

            }

            return Helpers::successResponse('B2B Pricing Plans', $plans);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function checkoutPlan(Request $request)
    {

        try {

            $data = Subscription::checkoutPlan($request);

            return Helpers::successResponse('Payment method has been created successfully!', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function processPlan(Request $request)
    {

        try {

            $plan = Subscription::processPlan($request);

            return Helpers::successResponse('Payment has been done successfully!', $plan);


        } catch (\Exception $exception) {

            if ($exception instanceof CardException) {

                return Helpers::validationResponse($exception->getMessage());

            } else {

                return Helpers::serverErrorResponse($exception->getMessage());
            }
        }
    }

    public function getCoupons(Request $request)
    {

        try {

            Stripe::setApiKey(config('cashier.secret')); // or env('STRIPE_SECRET')

            $coupons = Coupon::all();

            $data = [];

            foreach ($coupons as $coupon) {

                $data =[
                    'coupon_code' => $coupon['id'] ?? null,
                    'coupon_percentage' => $coupon['percent_off'] ?? null,
                    'coupon_duration' => $coupon['duration'] ?? null,
                ];
            }

            return Helpers::successResponse('B2B Coupon Lists', $data);


        } catch (\Exception $exception) {

            if ($exception instanceof CardException) {

                return Helpers::validationResponse($exception->getMessage());

            } else {

                return Helpers::serverErrorResponse($exception->getMessage());
            }
        }
    }

}
