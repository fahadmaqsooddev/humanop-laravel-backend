<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BillingInfo\BillingInfo;
use App\Models\Client\Plan\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionItem\SubscriptionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Coupon;
use Stripe\Exception\CardException;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Illuminate\Support\Facades\Storage;


class B2BSubscriptionController extends Controller
{

    protected $auth;

    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth:api')->except(['subscriptionUpdateWebhook']);

        $this->auth = Auth::guard('api');

        $this->user = $user;
    }

    public function pricingPlans()
    {
        try {

            Stripe::setApiKey(config('cashier.secret'));

            $prices = Plan::getB2BActivePlans();

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

    public function subscriptionUpdateWebhook(){

        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // If you are testing your webhook locally with the Stripe CLI you
        // can find the endpoint's secret by running `stripe listen`
        // Otherwise, find your endpoint's secret in your webhook settings in the Developer Dashboard
        $endpoint_secret = config('stripe_info.update_subscription_webhook_secret');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            echo json_encode(['Error parsing payload: ' => $e->getMessage()]);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            echo json_encode(['Error verifying webhook signature: ' => $e->getMessage()]);
            exit();
        }

        // Handle the event
        switch ($event->type) {

            case 'customer.subscription.updated':

                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent

                $cancel_subscription = $paymentIntent['canceled_at'];

                $customer_id = $paymentIntent['customer'] ?? null;

                $plan_id = $paymentIntent['items']['data'][0]['plan']['id'] ?? null;

                $product_id = $paymentIntent['items']['data'][0]['plan']['product'] ?? null;

                $user = User::where('stripe_id', $customer_id)->first();

                if ($user){ // User already subscribed to any subscription

                    $subs = Subscription::where('user_id', $user->id)->latest()->first(); // find it's subscription

                    if ($subs){

                        if (!empty($cancel_subscription)){ // if user cancel their subscription

                            $sub_item = SubscriptionItem::where('subscription_id', $subs->id)->first();

                            Subscription::where('user_id', $user->id)->delete(); // delete it's subscriptions

                            $sub_item->delete(); // delete it's subscription items

                        }else{ // if user update their plan

                            $sub_item = SubscriptionItem::where('subscription_id', $subs->id)->first();

                            $sub_item_id = $sub_item->id;

                            $remaining_sub_item = SubscriptionItem::where('subscription_id', $subs->id)->where('id', '!=', $sub_item_id)->get();

                            foreach ($remaining_sub_item as $items){

                                $items->delete();

                            }

                            $subs->update(['stripe_price' => $plan_id]);

                            if ($sub_item && ($sub_item->stripe_price != $plan_id)){

                                $sub_item->update(['stripe_product' => $product_id, 'stripe_price' => $plan_id]);

                            }

                        }


                    }else {

                        // restore the deleted subscription
                        $user->getDeletedSubscription()->restore();

                        $subs = Subscription::where('user_id', $user->id)->first();

                        if ($subs){

                            // restore the deleted subscription items
                            $subs->deletedSubscriptionItems()->restore();

                            $sub_item = SubscriptionItem::where('subscription_id', $subs->id)->first();

                            $subs->update(['stripe_price' => $plan_id]); // update the plan after subscription

                            if ($sub_item){

                                $sub_item->update(['stripe_product' => $product_id, 'stripe_price' => $plan_id]);

                            }

                        }

                    }

                    if ($user->stripe_invoice_id || !empty($paymentIntent['latest_invoice'])){

                        $invoice_id = $user->stripe_invoice_id ?? $paymentIntent['latest_invoice'];

                        $billing_info = $user->billingInfo()->first() ?? "";

                        if ($billing_info){

                            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

                            $invoice = $user->findInvoice($invoice_id)->toArray();

                            $charge_id = $invoice['charge'];

                            if ($charge_id){

                                $receipt_data = $stripe->charges->retrieve($charge_id, [])->toArray();

                                $url = parse_url($receipt_data['receipt_url']);

                                $final_link = $url['scheme'] . '://' . $url['host'] . $url['path'] . '/pdf?s=ap';

                                $pdf = GuzzleHelpers::getStripeReceiptPdf($final_link, 'Get');

                                $disk = Storage::disk('invoice');

                                $folder_name = config('stripe_info.invoice_folder_name') . '/' . date('Y');

                                $file_name = strtotime(Carbon::now()->format('Y-m-d H:i:s')) . "-" . $user->id;

                                $disk->put($folder_name . '/' . $file_name . '.pdf', $pdf);

                            }

                        }

                    }

                }

                break;

            case 'customer.subscription.deleted':

                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent

                $cancel_subscription = $paymentIntent['canceled_at'];

                $customer_id = $paymentIntent['customer'] ?? null;

                $user = User::where('stripe_id', $customer_id)->first();

                if ($user){ // User already subscribed to any subscription

                    $subs = Subscription::where('user_id', $user->id)->first(); // find it's subscription

                    if ($subs){

                        if (!empty($cancel_subscription)){ // if user cancel their subscription

                            $sub_item = SubscriptionItem::where('subscription_id', $subs->id)->first();

                            Subscription::where('user_id', $user->id)->delete(); // delete it's subscription

                            $sub_item->delete(); // delete it's subscription items

                        }

                    }

                }

                break;

            default:

                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);

    }


    public function getCoupons(Request $request)
    {

        try {

            Stripe::setApiKey(config('cashier.secret')); // or env('STRIPE_SECRET')

            $coupons = Coupon::all();

            $data = [];

            foreach ($coupons as $coupon) {

                $data[] =[
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
