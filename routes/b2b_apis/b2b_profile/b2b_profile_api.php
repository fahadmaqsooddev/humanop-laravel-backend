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
    // Route::post('b2b-profile-update','B2BAuthController@ProfileUpdate');
    // Route::post('create-b2b-support','B2BAuthController@Support');
    // Route::get('b2b-pricing-plans','B2BAuthController@pricingPlans');
    // Route::post('b2b-checkout','B2BAuthController@checkout');

});
