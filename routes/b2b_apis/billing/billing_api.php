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
    Route::get('b2b-pricing-plans','B2BSubscriptionController@pricingPlans');
    Route::post('b2b-checkout-plan','B2BSubscriptionController@checkoutPlan');
    Route::post('b2b-process-plan','B2BSubscriptionController@processPlan');

});
