<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| B2B API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('b2b-signup', 'B2BAuthController@b2bSignup');
Route::post('b2b-register-first-step','B2BAuthController@b2bRegisterFirstStep');
Route::post('b2b-register-second-step','B2BAuthController@b2bRegisterSecondStep');
Route::post('b2b-register-last-step','B2BAuthController@b2bRegisterLastStep');
Route::get('business-strategies', 'B2BAuthController@businessStrategies');
Route::get('get-business-sub-strategies', 'B2BAuthController@getBusinessSubStrategies');
Route::get('all-intentions','B2BAuthController@AllIntentions');
Route::get('b2b-account-check','B2BAuthController@b2bAccountCheck');



