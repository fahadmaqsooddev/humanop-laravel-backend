<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['checkUser']], function () {

    Route::post('payment_checkout', 'PaymentController@paymentCheckout');
    Route::post('redeem_coupon', 'PaymentController@redeemCoupon');
    Route::get('payment-history', 'PaymentController@paymentHistory');
    Route::get('billing', 'PaymentController@billing');
    Route::get('checkout-subscription', 'PaymentController@checkoutSubscription');
    Route::post('process-subscription', 'PaymentController@processSubscription');
    Route::get('plans', 'PaymentController@plans');


    Route::post('hai-credit-checkout', 'PaymentController@haiCreditCheckout');
    Route::get('hai-credit-plans','PaymentController@haiCreditPlans');

    Route::get('b2c-invoice', 'PaymentController@invoice');





    Route::post('/billing/subscriptions/init', [\App\Http\Controllers\Api\ClientController\Billing\B2CController::class, 'initSubscription']);
    Route::post('/billing/subscriptions/swap', [\App\Http\Controllers\Api\ClientController\Billing\B2CController::class, 'swapPlan']);     // Option A
    Route::post('/billing/subscriptions/cancel', [\App\Http\Controllers\Api\ClientController\Billing\B2CController::class, 'cancelAtPeriodEnd']);
    Route::post('/billing/subscriptions/resume', [\App\Http\Controllers\Api\ClientController\Billing\B2CController::class, 'resume']);
    Route::get('/billing/subscriptions/{stripeSubscriptionId}/status', [\App\Http\Controllers\Api\ClientController\Billing\B2CController::class, 'status']); // optional

    // One-time (Payment Element)
    Route::post('/billing/lifetime/init', [\App\Http\Controllers\Api\ClientController\Billing\B2CController::class, 'initLifetime']);     // client_secret
    Route::post('/billing/bb-onetime/init', [\App\Http\Controllers\Api\ClientController\Billing\B2CController::class, 'initBBOneTime']);  // client_secret


});
