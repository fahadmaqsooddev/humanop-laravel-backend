<?php

namespace App\Models;

use App\Helpers\Helpers;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Client\Plan\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\StripeClient;

class Subscription extends Model
{
    use HasFactory;
    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    public function plan(){

        return $this->belongsTo(Plan::class,'stripe_price', 'plan_id');
    }

    public static function checkoutPlan($request = null){

        $plan = \App\Models\Client\Plan\Plan::where('plan_id', $request->input('plan_id'))->first();

        if (!$plan){

            return Helpers::notFoundResponse("No plan found against. Please contact technical support.");
        }

        $data = [

            'plan' => $plan,

            'intent' => Helpers::getUser()->createSetupIntent(),
        ];

        return $data;

    }

    public static function processSubscription($request = null){

        if ($request->input('is_default_payment') == 0){ // when user continues with new payment method

            $key = StripeSetting::getSingle();

            $stripe_client = new StripeClient($key['api_key']);

            $new_payment_method_detail = $stripe_client->paymentMethods->retrieve($request->input('payment_method'), []); // then retrieve payment method detail from stripe to update our local db with payment detail

            User::updateUserPaymentMethodFromApi($new_payment_method_detail); // update local db with payment method detail

        }

        $user = Helpers::getUser();

        $user->createOrGetStripeCustomer();

        $payment_method = $user['payment_method']; // user payment method new/default

        if ($payment_method != null){ // if payment method is null then

            $key = StripeSetting::getSingle();

            $stripe_client = new StripeClient($key['api_key']);

            $payment_method = $stripe_client->paymentMethods->attach(
                'pm_card_visa', // when stripe test mood will off then change it to the user payment method
                ['customer' => $user['stripe_id']]
            );
        }

        if ($user->subscriptions()->whereNull('deleted_at')->count() > 0){

            $user->subscription('main')->swapAndInvoice($request->input('plan_id'));

        }else{

            $user->newSubscription('main' , $request->input('plan_id'))->create(isset($payment_method->id) ? $payment_method->id : $payment_method);

        }

//        DailyTip::hitDailyTipApiAndUpdateUserTip(Helpers::getWebUser());
//
//        ActionPlan::storeUserActionPlan(true);

        $plan = \App\Models\Client\Plan\Plan::singlePlan($request->input('plan_id'));

        $data = [
            'plan_name' => $plan['name']
        ];

        return $data;

    }

    // public static function allSubscriptions(){

    //     $subscriptions = self::all();

    //     dd($subscriptions);

    // }

    public static function updateUserSubscriptionFromAdmin($plan_id, $user_id){

        if ($user_id){

            $subscription = self::where('user_id', $user_id)->first();

            if ($subscription){

                $subscription->update(['stripe_price' => $plan_id]);

            }else{

                self::create([
                    'user_id' => $user_id,
                    'stripe_price' => $plan_id,
                    'name' => 'main',
                    'stripe_id' => rand(5,10),
                    'stripe_status' => 'active',
                    'quantity' => 1,
                ]);
            }

        }

    }

    public static function processPlan($request = null){

        $user = Helpers::getUser();

        $user->createOrGetStripeCustomer();

        $payment_method = $request->input('payment_method');

        if ($payment_method != null){

            $payment_method = $user->addPaymentMethod($payment_method);

        }

        $coupon = $request->input('coupon');

        $subscription = $user->subscription('main');


        if (!empty($subscription->ends_at)){

            $newSubscription = $user->newSubscription('main', $request->input('plan_id'));

            if (!empty($coupon)) {

                $newSubscription->withCoupon($coupon);

            }

            $newSubscription->create($payment_method !== null ? $payment_method->id : null);

        }else{

            if ($subscription && $subscription->valid()) {

                $newSubscription = $user->subscription('main')->swap($request->input('plan_id'));

                $newSubscription->create($payment_method !== null ? $payment_method->id : null);

//                $subscription->swapAndInvoice($request->input('plan_id'));

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
