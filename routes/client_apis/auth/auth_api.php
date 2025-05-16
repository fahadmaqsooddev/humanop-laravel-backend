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


Route::post('register-first-step', 'AuthController@registerFirstStep');
Route::post('check-email-verification', 'AuthController@checkEmailVerification');
Route::post('email-verified', 'AuthController@EmailVerified');
Route::get('resend-email-verification', 'AuthController@resendEmailVerification');
Route::post('send-sms-code', 'AuthController@sendSmsCode');
Route::post('check-sms-code-verification', 'AuthController@SmsCodeVerification');
Route::post('register-last-step', 'AuthController@registerLastStep');
Route::post('login-client', 'AuthController@loginClient');
Route::post('logout-client', 'AuthController@logoutClient');
Route::post('forgot-password', 'AuthController@forgotPassword');
Route::post('social-login', 'AuthController@socialLogin');
Route::get('intention-option', 'AuthController@intentionOption');
Route::get('check-invite-link', 'AuthController@checkInviteLink');
Route::post('send-phone-otp', 'AuthController@sendPhoneOtp');
Route::get('check-candidate', 'AuthController@checkUserDetail');

// invite link Create Api
Route::get('invite','AuthController@SendInvite');

Route::get('user-info-for-hai','AuthController@getUserInfoForHai');
