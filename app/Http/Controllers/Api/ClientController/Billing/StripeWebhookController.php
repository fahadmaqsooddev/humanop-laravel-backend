<?php

namespace App\Http\Controllers\Api\ClientController\Billing;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\StripeConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhook;
use Stripe\StripeClient;
use Stripe\Webhook as StripeWebhook;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{

    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sig = $request->header('stripe-signature');
        Log::info($sig);
dd('1');
        // Signature verification using DB webhook secret
        StripeWebhook::constructEvent(
            $payload,
            $sig,
            StripeConfig::webhookSecret()
        );

        // Let Cashier do its default sync first
        $response = app(CashierWebhook::class)->handleWebhook($request);

        $event = json_decode($payload, true);
        $type = $event['type'] ?? '';

        try {
            $stripe = app(StripeClient::class);

            switch ($type) {

                case 'customer.subscription.created':
                {
                    // Mainly for freemium. We already set the user on signup,
                    // so we just log for analytics.
                    $sub = $event['data']['object'] ?? [];
                    $purpose = $sub['metadata']['purpose'] ?? null;
                    if ($purpose === 'freemium') {
                        Log::info('Freemium subscription created', [
                            'stripe_subscription' => $sub['id'] ?? null,
                            'customer' => $sub['customer'] ?? null,
                        ]);
                    }
                    break;
                }

                case 'invoice.payment_succeeded':
                {
                    $invoice = $event['data']['object'] ?? [];
                    $customerId = $invoice['customer'] ?? null;

                    if ($customerId) {
                        $user = User::where('stripe_id', $customerId)->first();
                        if ($user) {
                            // Expand invoice to get product names for logging
                            $inv = $stripe->invoices->retrieve(
                                $invoice['id'],
                                ['expand' => ['lines.data.price.product']]
                            );

                            foreach ($inv->lines->data as $line) {
                                $productName = $line->price->product->name ?? null;
                                if ($productName) {
                                    Log::info('Invoice line product', [
                                        'user_id' => $user->id,
                                        'invoice' => $inv->id,
                                        'product' => $productName,
                                        'price' => $line->price->id,
                                    ]);
                                }
                            }

                            // If this invoice is for an active subscription payment,
                            // mark user as premium (non-lifetime).
                            if (!empty($invoice['subscription'])) {
                                $user->is_lifetime = false;
                                $user->billing_context = 'b2c';
                                // keep current plan (we set plan optimistically in initSubscription),
                                // but if it's empty, set a fallback:
                                if (!$user->plan || $user->plan === 'free') {
                                    $user->plan = 'active_recurring';
                                }
                                $user->save();
                            }
                        }
                    }

                    break;
                }

                case 'invoice.payment_failed':
                {
                    // Stripe will retry automatically. We'll let
                    // 'customer.subscription.updated' / 'deleted' handle final downgrade.
                    $invoice = $event['data']['object'] ?? [];
                    Log::warning('Invoice payment failed', [
                        'customer' => $invoice['customer'] ?? null,
                        'invoice' => $invoice['id'] ?? null,
                    ]);
                    break;
                }

                case 'customer.subscription.updated':
                case 'customer.subscription.deleted':
                {
                    $sub = $event['data']['object'] ?? [];
                    $status = $sub['status'] ?? '';
                    $customerId = $sub['customer'] ?? null;

                    // If Stripe says this sub is effectively dead after retries,
                    // downgrade unless they are lifetime
                    if (in_array($status, ['canceled', 'unpaid', 'incomplete_expired'])) {
                        $user = $customerId
                            ? User::where('stripe_id', $customerId)->first()
                            : null;

                        if ($user && !$user->is_lifetime) {
                            $user->plan = 'free';
                            $user->billing_context = 'b2c';
                            $user->save();
                        }
                    }

                    break;
                }

                // One-time pay flows (lifetime and bb_onetime)
                case 'payment_intent.succeeded':
                case 'charge.succeeded':
                {
                    $obj = $event['data']['object'] ?? [];
                    $md = $obj['metadata'] ?? [];
                    $purpose = $md['purpose'] ?? null;
                    $customer = $obj['customer'] ?? null;
                    $user = $customer
                        ? User::where('stripe_id', $customer)->first()
                        : null;

                    if (!$user) {
                        break;
                    }

                    // Mark which "side" sold them. Today always 'b2c'.
                    $user->billing_context = 'b2c';

                    if ($purpose === 'lifetime') {
                        $planKey = $md['plan'] ?? 'premium_lifetime';

                        $user->plan = $planKey;
                        $user->is_lifetime = true;

//                        if ($type === 'payment_intent.succeeded') {
//                            $user->stripe_lifetime_payment_intent_id = $obj['id'] ?? null;
//                        } else {
//                            $user->stripe_lifetime_charge_id = $obj['id'] ?? null;
//                        }

                        $user->save();

                        Log::info('Lifetime activated', [
                            'user_id' => $user->id,
                            'plan' => $planKey,
                            'object' => $obj['id'] ?? null,
                            'product' => $md['product_name'] ?? null,
                        ]);
                    }

                    if ($purpose === 'bb_onetime') {
                        $user->has_bb_onetime = true;
                        $user->save();

                        Log::info('BB-Onetime granted', [
                            'user_id' => $user->id,
                            'object' => $obj['id'] ?? null,
                            'product' => $md['product_name'] ?? null,
                        ]);
                    }

                    break;
                }
            }
        } catch (\Throwable $e) {
            Log::error('Webhook post-processing failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return $response;
    }
}
