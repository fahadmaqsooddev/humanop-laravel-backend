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

Route::post('login-client','AuthController@loginClient');
Route::post('register-client', 'AuthController@registerClient');
Route::post('logout-client', 'AuthController@logoutClient');
Route::post('forgot-password', 'AuthController@forgotPassword');
Route::post('social-login', 'AuthController@socialLogin');
Route::get('version', 'AuthController@appVersion');
