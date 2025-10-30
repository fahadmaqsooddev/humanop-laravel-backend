<?php

namespace App\Http\Controllers\Api\ClientController\Billing;

use App\Http\Controllers\Controller;
use App\Mail\InvoicePaidMail;
use App\Models\User;
use App\Support\StripeConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $sig     = $request->header('stripe-signature');

        // 1) Verify signature early (fail fast)
        try {
            StripeWebhook::constructEvent(
                $payload,
                $sig,
                StripeConfig::webhookSecret()
            );
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        // 2) Let Cashier do its sync first (customers, subs, invoices tables, etc.)
        $response = app(CashierWebhook::class)->handleWebhook($request);

        // 3) Parse and branch on type (post-processing)
        $event = json_decode($payload, true);
        $type  = $event['type'] ?? '';

        Log::info("Stripe webhook received: {$type}");

        try {

            $stripe = app(StripeClient::class);

            switch ($type) {

                // ---- Light ping events --------------------------------------------------------
                case 'ping':
                case 'app.handshake':
                    Log::info('Stripe webhook handshake/ping OK');
                    break;

                // ---- Freemium bookkeeping (mostly analytics) ---------------------------------
                case 'customer.subscription.created':
                {
                    $sub = $event['data']['object'] ?? [];
                    $purpose = $sub['metadata']['purpose'] ?? null;
                    if ($purpose && strcasecmp($purpose, 'freemium') === 0) {
                        Log::info('Freemium subscription created', [
                            'stripe_subscription' => $sub['id'] ?? null,
                            'customer'            => $sub['customer'] ?? null,
                        ]);
                    }
                    break;
                }

                // ---- When the first/recurring invoice is PAID --------------------------------
                case 'invoice.payment_succeeded':
                {
                    $invoice = $event['data']['object'] ?? [];
                    $customerId = $invoice['customer'] ?? null;
                    if (!$customerId) break;

                    $user = User::where('stripe_id', $customerId)->first();
                    if (!$user) break;

                    // Re-retrieve with expansions for richer email + product names
                    $inv = $stripe->invoices->retrieve($invoice['id'], [
                        'expand' => ['lines.data.price.product', 'payment_intent', 'charge'],
                    ]);

                    // Idempotency guard for emails (avoid duplicates on webhook retries)
                    $cacheKey = 'invoice_email_sent:' . $inv->id;
                    if (!Cache::add($cacheKey, true, now()->addDays(7))) {
                        Log::info('Invoice email already sent; skipping', ['invoice' => $inv->id, 'user_id' => $user->id]);
                        break;
                    }

                    // Build compact lines for your mailable
                    $lines = collect($inv->lines->data)->map(function ($l) use ($inv) {
                        return [
                            'description' => $l->description,
                            'amount'      => $l->amount, // cents
                            'currency'    => strtoupper($inv->currency),
                            'product'     => $l->price->product->name ?? null,
                            'quantity'    => $l->quantity ?? 1,
                        ];
                    })->all();

                    $emailPayload = [
                        'invoice_id'     => $inv->id,
                        'number'         => $inv->number,
                        'hosted_url'     => $inv->hosted_invoice_url,
                        'pdf_url'        => $inv->invoice_pdf,
                        'total'          => $inv->total, // cents
                        'currency'       => strtoupper($inv->currency),
                        'status'         => $inv->status, // "paid"
                        'created'        => $inv->created,
                        'lines'          => $lines,
                        'customer_email' => $inv->customer_email ?: $user->email,
                        'customer_name'  => $inv->customer_name ?: trim($user->first_name . ' ' . $user->last_name),
                    ];

                    // Queue the invoice-paid email
                    try {
                        Mail::to($user->email)->queue(
                            (new InvoicePaidMail($user, $emailPayload))->onQueue('billing')
                        );
                        Log::info('Invoice paid email queued', ['invoice' => $inv->id, 'user_id' => $user->id]);
                    } catch (\Throwable $e) {
                        Log::error('Invoice paid email queue failed', ['invoice' => $inv->id, 'error' => $e->getMessage()]);
                    }

                    // Keep user state aligned for recurring subs (do not touch lifetime)
                    if (!empty($inv->subscription) && !$user->is_lifetime) {
                        $user->billing_context = 'b2c';
                        // If you didn’t set a specific plan label elsewhere, keep a generic active marker
                        if (!$user->plan || $user->plan === 'free') {
                            $user->plan = 'active_recurring';
                        }
                        $user->save();
                    }

                    break;
                }

                // ---- First-charge or retry failures (Stripe retries automatically) ------------
                case 'invoice.payment_failed':
                {
                    $invoice = $event['data']['object'] ?? [];
                    Log::warning('Invoice payment failed', [
                        'customer' => $invoice['customer'] ?? null,
                        'invoice'  => $invoice['id'] ?? null,
                    ]);
                    // You may notify user to update their card via in-app banner/email.
                    break;
                }

                // ---- Subscription status transitions (terminal states) ------------------------
                case 'customer.subscription.updated':
                case 'customer.subscription.deleted':
                {
                    $sub        = $event['data']['object'] ?? [];
                    $status     = $sub['status'] ?? '';
                    $customerId = $sub['customer'] ?? null;

                    if (in_array($status, ['canceled', 'unpaid', 'incomplete_expired'], true)) {
                        $user = $customerId ? User::where('stripe_id', $customerId)->first() : null;
                        if ($user && !$user->is_lifetime) {
                            $user->plan = 'free';
                            $user->billing_context = 'b2c';
                            $user->save();
                            Log::info('User downgraded to free due to subscription status', [
                                'user_id' => $user->id,
                                'status'  => $status,
                            ]);
                        }
                    }
                    break;
                }

                // ---- One-time flows: Lifetime + BB-Onetime (off-session OR on-session) --------
                case 'payment_intent.succeeded':
                case 'charge.succeeded':
                {
                    $obj      = $event['data']['object'] ?? [];
                    $metadata = $obj['metadata'] ?? [];
                    $purpose  = $metadata['purpose'] ?? null;
                    $customer = $obj['customer'] ?? null;

                    $user = $customer ? User::where('stripe_id', $customer)->first() : null;
                    if (!$user) break;

                    $user->billing_context = 'b2c';

                    if ($purpose === 'lifetime') {
                        $planKey = $metadata['plan'] ?? 'premium_lifetime';
                        $user->plan        = $planKey;
                        $user->is_lifetime = true;
                        $user->save();

                        Log::info('Lifetime activated', [
                            'user_id' => $user->id,
                            'plan'    => $planKey,
                            'object'  => $obj['id'] ?? null,
                            'product' => $metadata['product_name'] ?? null,
                        ]);
                    }

                    if ($purpose === 'bb_onetime') {
                        $user->has_bb_onetime = true;
                        $user->save();

                        Log::info('BB-Onetime granted', [
                            'user_id' => $user->id,
                            'object'  => $obj['id'] ?? null,
                            'product' => $metadata['product_name'] ?? null,
                        ]);
                    }

                    break;
                }

                // ---- Keep local masked PM columns in sync when defaults change ----------------
                case 'customer.updated':
                {
                    $cust = $event['data']['object'] ?? [];
                    $customerId = $cust['id'] ?? null;
                    if (!$customerId) break;

                    $user = User::where('stripe_id', $customerId)->first();
                    if (!$user) break;

                    // If default PM changed, refresh local columns (payment_method, pm_last_four, etc.)
                    try {
                        $user->syncDefaultPmFromStripe($stripe); // our helper on User model
                        Log::info('Synced default PM from customer.updated', ['user_id' => $user->id]);
                    } catch (\Throwable $e) {
                        Log::warning('Failed syncing PM on customer.updated', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                    }
                    break;
                }

                case 'payment_method.attached':
                case 'payment_method.automatically_updated':
                {
                    $pm = $event['data']['object'] ?? [];
                    $customerId = $pm['customer'] ?? null;
                    if (!$customerId) break;

                    $user = User::where('stripe_id', $customerId)->first();
                    if (!$user) break;

                    // Only sync if this PM is (or just became) the default on the customer
                    try {
                        $customer = $stripe->customers->retrieve($customerId, [
                            'expand' => ['invoice_settings.default_payment_method'],
                        ]);
                        $defaultPmId = $customer->invoice_settings->default_payment_method->id ?? null;

                        if ($defaultPmId) {
                            $user->syncDefaultPmFromStripe($stripe, $defaultPmId);
                            Log::info('Synced default PM from payment_method.* event', ['user_id' => $user->id]);
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Failed syncing PM on payment_method.*', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                    }

                    break;
                }
            }

        } catch (\Throwable $e) {
            Log::error('Stripe webhook post-processing failed', ['error' => $e->getMessage(), 'type' => $type]);
        }

        // Always return what Cashier returned (usually 200)
        return $response;
    }
}
