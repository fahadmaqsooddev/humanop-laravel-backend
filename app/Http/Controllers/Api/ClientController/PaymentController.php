<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\CheckoutPaymentRequest;
use App\Http\Requests\Api\Client\RedeemCouponRequest;
use App\Http\Requests\Api\Client\Subscription\CheckoutSubscriptionRequest;
use App\Http\Requests\Api\Client\Subscription\ProcessSubscriptionRequest;
use App\Models\Admin\Coupon\Coupon;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Client\Plan\Plan;
use App\Models\Client\Point\Point;
use App\Models\Client\PurchasedItems;
use App\Models\Customization\Customization;
use App\Models\Payment;
use App\Models\Plan\CreditPlan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\Charge;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function paymentCheckout(CheckoutPaymentRequest $request)
    {

        DB::beginTransaction();

        try {

            $user = Helpers::getUser();

            $user->createOrGetStripeCustomer();

            $stripe = StripeSetting::getSingle();

            if ($stripe) {

                $stripe_client = new StripeClient($stripe['api_key']);

                if (Str::contains($request->input('card_number'), '*') && $user['payment_method']) {

                    $user->charge($request->input('price') * 100, $user['payment_method'], [
                        'currency' => 'usd',
                        'description' => 'Test Payment',
                    ]);

                } else {

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
                $data['coupon_id'] = $coupon->id ?? null;
                $data['assessment_id'] = $assessment->id ?? null;
                $data['discount_price'] = $request['price'];
                $data['total_price'] = $stripe['amount'];

                Payment::createPaymentFromApi($data);

                DB::commit();

                return Helpers::successResponse('Payment successfully charged');
            }

            DB::rollBack();

            return Helpers::validationResponse('Something went wrong while charging');

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function redeemCoupon(RedeemCouponRequest $request)
    {

        try {

            $original_amount = StripeSetting::getSingle();

            $response = Coupon::redeemCouponCodeForApi($request->input('coupon_code'), $original_amount['amount']);

            return $response;

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function paymentHistory(Request $request)
    {

        try {

            $user_id = Helpers::getUser()->id;

            $payments = Payment::paginatedPaymentHistory($request, $user_id);

            return Helpers::successResponse('Payment History', $payments, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public static function billing()
    {

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

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function checkoutSubscription(CheckoutSubscriptionRequest $request)
    {

        try {

            $data = Subscription::checkoutPlan($request);

            return Helpers::successResponse('Payment method has been created successfully!', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function haiCreditCheckout(Request $request)
    {

        try {

            DB::beginTransaction();

            Stripe::setApiKey(config('cashier.secret'));

            $charge = Charge::create([
                "amount" => $request['amount'] * 100, // amount in cents
                "currency" => "usd",
                "source" => 'tok_visa',
//                "source" => $request['stripeToken'],
                "description" => "HAI CREDIT Payment"
            ]);

            if ($charge && $charge->status === 'succeeded') {

                $credits = CreditPlan::where('price', $request['amount'])->first()->credits ?? 0;

                if ($credits > 0) {

                    Point::addPoints($credits);
                }

                $name = "You have purchased Hai item";

                PurchasedItems::createItem(Helpers::getUser()['id'], $name, $request['price'], Admin::B2C_PURCHASED_ITEM);

                DB::commit();

                return Helpers::successResponse("You've successfully received {$credits} credits based on your plan!");

            } else {

                return Helpers::validationResponse("Payment failed. Please try again.");
            }

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function processSubscription(ProcessSubscriptionRequest $request)
    {

        try {

            $plan_name = Subscription::processSubscription($request);

            return Helpers::successResponse('Subscription is updated', $plan_name);

        } catch (\Exception $exception) {

            if ($exception instanceof CardException) {

                return Helpers::validationResponse($exception->getMessage());

            } else {

                return Helpers::serverErrorResponse($exception->getMessage());
            }
        }

    }

    public static function plans()
    {

        try {

            $plans = Plan::getB2CPlans();

            return Helpers::successResponse('All plans', $plans);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function haiCreditPlans()
    {

        try {

            $plans = CreditPlan::allPlans();

            $hai_credit = Customization::where('detail', Customization::HP_TO_HAI_CREDITS)->value('points');

            $user_hp = HumanOpPoints::where('user_id', Helpers::getUser()->id)->value('points');

            $data = [
                'plans' => $plans,
                'available_hp' => $user_hp,
                'one_hai_credit' => $hai_credit,
            ];

            return Helpers::successResponse('All plans', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function invoice()
    {
        try {
            $user = Helpers::getUser();

            $subscription = $user->subscription('main');

            $key = StripeSetting::getSingle();

            $stripe = new StripeClient($key->api_key);

            $stripeSubscription = $subscription->asStripeSubscription();

            $invoice = $stripe->invoices->retrieve($stripeSubscription->latest_invoice, []);

            $paidAmount = $invoice->amount_paid / 100;

            dd($paidAmount);
            
            $purchasedItems = PurchasedItems::getPurchasedItems($user['id']);

            $totalPrice = 0;
            $items = [];

            foreach ($purchasedItems as $purchasedItem) {
                $totalPrice += $purchasedItem->item_price;
                $items[] = [
                    'item_name' => $purchasedItem->item_name,
                    'item_price' => $purchasedItem->item_price,
                    'item_purchased_at' => $purchasedItem->created_at,
                ];
            }

            $invoice = [
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'email' => $user['email'],
                'pone_number' => $user['phone'],
            ];

            if ($user['plan_name'] !== 'Freemium') {
                $dueDate = null;

                if ($subscription && $subscription->active()) {
                    $dueDate = Carbon::createFromTimestamp(
                        $subscription->asStripeSubscription()->current_period_end
                    )->toDateTimeString();
                }

                $invoice['plan_name'] = $user['plan_name'];
                $invoice['plan_price'] = 19;
                $invoice['due_date'] = $dueDate;
            }

            foreach ($items as $index => $item) {
                $invoice['item_name_' . ($index + 1)] = $item['item_name'];
                $invoice['item_price_' . ($index + 1)] = $item['item_price'];
                $invoice['item_purchased_at_' . ($index + 1)] = $item['item_purchased_at'];
            }

            $invoice['total_price'] = $totalPrice + ($user['plan_name'] !== 'Freemium' ? $paidAmount : 0);

            return Helpers::successResponse('HumanOp Invoice', $invoice);

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


}
