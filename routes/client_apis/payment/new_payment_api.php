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

Route::group(['middleware' => ['checkUser']], function () {

    Route::get('billing/payment-method/init','BillingController@initPaymentMethod');

    Route::post('/billing/payment-method/finalize','BillingController@finalizePaymentMethod');

    Route::get ('/billing/payment-method','BillingController@getDefaultPaymentMethod');


    // Create premium subscription draft (monthly/yearly)
    Route::post('billing/subscriptions/init', 'BillingController@initSubscription');

    // Swap recurring plan (premium_monthly <-> premium_yearly)
    Route::post('billing/subscriptions/swap', 'BillingController@swapPlan');

    // Cancel / resume
    Route::post('billing/subscriptions/cancel', 'BillingController@cancelAtPeriodEnd');
    Route::post('billing/subscriptions/resume', 'BillingController@resume');

    // Poll status by Stripe sub ID
    Route::get('billing/subscriptions/{stripeSubscriptionId}/status', 'BillingController@status');

    // Lifetime purchase (one-time)
    Route::post('billing/lifetime/init', 'BillingController@initLifetime');

    // BB-onetime / add-on (one-time)
    Route::post('billing/bb-onetime/init', 'BillingController@initBBOneTime');

});
