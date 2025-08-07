<?php

namespace App\Models;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Client\Plan\Plan;
use App\Models\Client\Point\Point;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\StripeClient;

class Subscription extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function plan()
    {

        return $this->belongsTo(Plan::class, 'stripe_price', 'plan_id');
    }

    public static function checkoutPlan($request = null)
    {

        $plan = \App\Models\Client\Plan\Plan::where('plan_id', $request->input('plan_id'))->first();

        if (!$plan) {

            return Helpers::notFoundResponse("No plan found against. Please contact technical support.");
        }

        $data = [

            'plan' => $plan,

            'intent' => Helpers::getUser()->createSetupIntent(),
        ];

        return $data;

    }

    public static function processSubscription($request = null)
    {
        $key = StripeSetting::getSingle();

        if (!$key || empty($key->api_key)) {
            throw new \Exception('Stripe API key not configured.');
        }

        \Stripe\Stripe::setApiKey($key->api_key);

        $stripe_client = new \Stripe\StripeClient($key->api_key);

        $payment_method_id = $request->input('payment_method');

        dd($payment_method_id);
        $new_payment_method_detail = $stripe_client->paymentMethods->retrieve($payment_method_id, []);

        dd($new_payment_method_detail);
        User::updateUserPaymentMethodFromApi($new_payment_method_detail);

        $user = Helpers::getUser();

        $user->createOrGetStripeCustomer();

        // Attach payment method to customer
        $stripe_client->paymentMethods->attach($payment_method_id, [
            'customer' => $user->b2c_stripe_id
        ]);

        // Set default payment method
        \Stripe\Customer::update($user->b2c_stripe_id, [
            'invoice_settings' => [
                'default_payment_method' => $payment_method_id,
            ],
        ]);

        $subscription = $user->subscription('main');

        if ($subscription && $subscription->stripe_status !== 'incomplete') {
            $subscription->swapAndInvoice($request->input('plan_id'));
        } else {
            if ($subscription && $subscription->stripe_status === 'incomplete') {
                $subscription->cancelNow();
            }

            $user->newSubscription('main', $request->input('plan_id'))->create($payment_method_id);
        }


        $plan = \App\Models\Client\Plan\Plan::singlePlan($request->input('plan_id'));

        if ($plan && $plan->name === 'Core') {

            Point::updatePointOnPlanUpdate(Admin::CORE_CREDITS, $user);
        }

        return [
            'plan_name' => $plan->name ?? null
        ];
    }

    public static function updateUserSubscriptionFromAdmin($plan_id, $user_id)
    {

        if ($user_id) {

            $subscription = self::where('user_id', $user_id)->first();

            if ($subscription) {

                $subscription->update(['stripe_price' => $plan_id]);

            } else {

                self::create([
                    'user_id' => $user_id,
                    'stripe_price' => $plan_id,
                    'name' => 'main',
                    'stripe_id' => rand(5, 10),
                    'stripe_status' => 'active',
                    'quantity' => 1,
                ]);
            }

        }

    }

    public static function processPlan($request = null)
    {

        $user = Helpers::getUser();

        $user->createOrGetStripeCustomer();

        $payment_method = $request->input('payment_method');

        if ($payment_method != null) {

            $payment_method = $user->addPaymentMethod($payment_method);

        }

        $coupon = $request->input('coupon');

        $subscription = $user->subscription('main');


        if (!empty($subscription->ends_at)) {

            $newSubscription = $user->newSubscription('main', $request->input('plan_id'));

            if (!empty($coupon)) {

                $newSubscription->withCoupon($coupon);

            }

            $newSubscription->create($payment_method !== null ? $payment_method->id : null);

        } else {

            if ($subscription && $subscription->valid()) {

                $subscription->swap($request->input('plan_id'));

            } else {

                $newSubscription = $user->newSubscription('main', $request->input('plan_id'));

                if (!empty($coupon)) {

                    $newSubscription->withCoupon($coupon);

                }

                $newSubscription->create($payment_method !== null ? $payment_method->id : null);

            }

        }

        $plan = Plan::singlePlan($request->input('plan_id'));

        $data = [

            'plan_name' => $plan['name']

        ];

        return $data;

    }

}
