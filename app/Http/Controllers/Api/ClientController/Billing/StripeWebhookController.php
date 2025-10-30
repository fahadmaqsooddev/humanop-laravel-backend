<?php

namespace App\Http\Controllers\Api\ClientController\Billing;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\StripeConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        Log::info("Event payload:\n" . json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        try {
            $stripe = app(StripeClient::class);

            switch ($type) {

                case 'customer.subscription.created':
                {
                    // Mainly for freemium. We already set the user on signup,
                    // so we just log for analytics.
                    $sub = $event['data']['object'] ?? [];
                    $purpose = $sub['metadata']['purpose'] ?? null;
                    if ($purpose === 'freemium' || $purpose === 'Freemium') {
                        Log::info('Freemium subscription created', [
                            'stripe_subscription' => $sub['id'] ?? null,
                            'customer' => $sub['customer'] ?? null,
                        ]);
                    }
                    break;
                }

                case 'invoice.payment_succeeded':
                {

                    $invoiceObj = $event['data']['object'] ?? [];
                    $customerId = $invoiceObj['customer'] ?? null;

                    if (!$customerId) break;

                    $user = User::where('stripe_id', $customerId)->first();
                    if (!$user) break;

                    // Re-retrieve invoice with useful expansions
                    $inv = $stripe->invoices->retrieve($invoiceObj['id'], [
                        'expand' => ['lines.data.price.product', 'payment_intent', 'charge'],
                    ]);

                    // --- Idempotency guard: prevent duplicate emails on webhook retries ---
                    // Use Cache or DB; Cache::add returns true only if key didn't exist

                    $cacheKey = 'invoice_email_sent:'.$inv->id;

                    if (!\Illuminate\Support\Facades\Cache::add($cacheKey, true, now()->addDays(7))) {
                        // Already processed this invoice email; skip
                        Log::info('Invoice email already sent; skipping', ['invoice' => $inv->id, 'user_id' => $user->id]);
                        break;
                    }

                    // --- Prepare email payload ---
                    $lines = collect($inv->lines->data)->map(function ($l) use ($inv) {
                        return [
                            'description' => $l->description,
                            'amount'      => $l->amount, // cents
                            'currency'    => strtoupper($inv->currency),
                            'product'     => $l->price->product->name ?? null,
                            'quantity'    => $l->quantity ?? 1,
                        ];
                    })->all();

                    $payload = [
                        'invoice_id'     => $inv->id,
                        'number'         => $inv->number,
                        'hosted_url'     => $inv->hosted_invoice_url,
                        'pdf_url'        => $inv->invoice_pdf,
                        'total'          => $inv->total, // cents
                        'currency'       => strtoupper($inv->currency),
                        'status'         => $inv->status, // "paid"
                        'created'        => $inv->created,
                        'lines'          => $lines,
                        'customer_email' => $inv->customer_email ?? $user->email,
                        'customer_name'  => $inv->customer_name ?? trim($user->first_name.' '.$user->last_name),
                    ];

                    // --- Queue email (use your mailable) ---
                    // Make sure you have: app/Mail/InvoicePaidMail.php and a Blade view.
                    Mail::to($user->email)->queue(new \App\Mail\InvoicePaidMail($user, $payload));

                    Log::info('Invoice paid email queued', ['invoice' => $inv->id, 'user_id' => $user->id]);

                    // --- Keep user state aligned for recurring subs (non-lifetime) ---
                    if (!empty($inv->subscription)) {
                        $user->is_lifetime     = false;
                        $user->billing_context = 'b2c';
                        if (!$user->plan || $user->plan === 'free') {
                            // Fallback label if your optimistic set didn't run
                            $user->plan = 'active_recurring';
                        }
                        $user->save();
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
