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


Route::post('login-client', 'AuthController@loginClient');
Route::post('logout-client', 'AuthController@logoutClient');
Route::post('forgot-password', 'AuthController@forgotPassword');
Route::post('social-login', 'AuthController@socialLogin');
Route::get('version', 'AuthController@appVersion');
Route::get('resend-email-verification', 'AuthController@resendEmailVerification');
Route::get('intention-option', 'UserController@intentionOption');
Route::get('check-invite-link', 'AuthController@checkInviteLink');
Route::post('send-phone-otp', 'AuthController@sendPhoneOtp');

Route::post('email-verified', 'AuthController@EmailVerified');
Route::get('check-candidate', 'AuthController@checkUserDetail');
Route::post('register-first-step', 'AuthController@registerFirstStep');
Route::post('check-email-verification', 'AuthController@checkEmailVerification');
Route::post('register-last-step', 'AuthController@registerLastStep');

// invite link Create Api
Route::get('invite','AuthController@SendInvite');
