<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StripePublicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['checkUser','assessment']], function () {

    Route::post('payment_checkout', 'PaymentController@paymentCheckout');
    Route::post('redeem_coupon', 'PaymentController@redeemCoupon');
    Route::get('payment-history', 'PaymentController@paymentHistory');
    Route::get('billing', 'PaymentController@billing');
    Route::get('checkout-subscription', 'PaymentController@checkoutSubscription');
    Route::post('process-subscription', 'PaymentController@processSubscription');
    Route::post('hai-credit-checkout', 'PaymentController@haiCreditCheckout');
    Route::get('hai-credit-plans', 'PaymentController@haiCreditPlans');
    Route::get('b2c-invoice', 'PaymentController@invoice');




//    // Create premium subscription draft (monthly/yearly)
//    Route::post('/billing/subscriptions/init', 'BillingController@initSubscription');
//
//    // Swap recurring plan (premium_monthly <-> premium_yearly)
//    Route::post('/billing/subscriptions/swap', 'BillingController@swapPlan');
//
//    // Cancel / resume
//    Route::post('/billing/subscriptions/cancel', 'BillingController@cancelAtPeriodEnd');
//    Route::post('/billing/subscriptions/resume', 'BillingController@resume');
//
//    // Poll status by Stripe sub ID
//    Route::get('/billing/subscriptions/{stripeSubscriptionId}/status', 'BillingController@status');
//
//    // Lifetime purchase (one-time)
//    Route::post('/billing/lifetime/init', 'BillingController@initLifetime');
//
//    // BB-onetime / add-on (one-time)
//    Route::post('/billing/bb-onetime/init', 'BillingController@initBBOneTime');
});
