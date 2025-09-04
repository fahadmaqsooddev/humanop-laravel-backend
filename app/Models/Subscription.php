<?php

namespace App\Models;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Client\Plan\Plan;
use App\Models\Client\Point\Point;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Stripe;
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

        $user = Helpers::getUser();

        $key = StripeSetting::getSingle();

        if (!$key || empty($key->api_key)) {
            throw new \Exception('Stripe API key not configured.');
        }

        Stripe::setApiKey($key->api_key);

        $stripe_client = new StripeClient($key->api_key);

        $user->createOrGetStripeCustomer();

        $payment_method_id = $request->input('payment_method');

        if ($payment_method_id) {

            $new_payment_method_detail = $stripe_client->paymentMethods->retrieve($payment_method_id, []);

            User::updateUserPaymentMethodFromApi($new_payment_method_detail);

            $stripe_client->paymentMethods->attach($payment_method_id, [
                'customer' => $user->b2c_stripe_id
            ]);

            Customer::update($user->b2c_stripe_id, [
                'invoice_settings' => [
                    'default_payment_method' => $payment_method_id,
                ],
            ]);

        }

        $subscription = $user->subscription('main');

        if ($subscription && $subscription->stripe_status !== 'incomplete') {

            $subscription->swapAndInvoice($request->input('plan_id'),
                [
                    'proration_behavior' => 'create_prorations'
                ]
            );

        } else {

            if ($subscription && $subscription->stripe_status === 'incomplete') {

                $subscription->cancelNow();

            }

            $subscription = $user->newSubscription('main', $request->input('plan_id'))->create($payment_method_id, [
                'proration_behavior' => 'create_prorations'
            ]);

        }

        $user->set_daily_tip_time = '12:00:00';

        $user->save();

        $plan = Plan::singlePlan($request->input('plan_id'));

        if ($plan && $plan->name === 'Core') {

            Point::updatePointOnPlanUpdate(Admin::CORE_CREDITS, $user);
        }

        $stripeSubscription = $subscription->asStripeSubscription();

        $invoice = Invoice::retrieve($stripeSubscription->latest_invoice);

        $invoicePdf = $invoice->invoice_pdf ?? null;

        $template = EmailTemplate::getEmailTemplateByTag(Admin::INVOICE_CODE);

        $emailData = self::prepareEmailData($user, $invoicePdf, null, $template->body, $template->subject);

        self::sendEmailVerification($emailData, $user['email'], Admin::INVOICE_CODE, $template->name);

        return [
            'plan_name' => $plan->name == 'Freemium' ? "Subscription is downgraded" : "Subscription is upgraded"
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

    public static function prepareEmailData($user = null, $url = null, $codeNumber = null, $body = null, $subject = null)
    {
        return [
            '{$userName}' => $user['first_name'] . ' ' . $user['last_name'],
            '{$link}' => $url,
            '{$code}' => $codeNumber,
            '{$subject}' => $subject,
            '{$body}' => $body,
            '{$logo}' => URL::asset('assets/logos/HumanOp Logo.png'),
            '{$service}' => url('/term-of-service'),
            '{$privacy}' => url('/privacy-policy'),
        ];
    }

    public static function sendEmailVerification($emailData, $recipientEmail, $name, $subject)
    {
        $emailTemplate = EmailTemplate::getTemplate($emailData, $name);

        Email::sendEmailVerification(
            ['content' => $emailTemplate],
            $recipientEmail,
            'emails.Email_Template',
            $subject ? $subject : $name
        );
    }

}
