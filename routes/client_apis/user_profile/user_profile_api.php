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

    Route::get('user-profile', 'UserController@userProfile');
    Route::post('update-user-profile', 'UserController@updateUserProfile');
    Route::post('update-user-image', 'UserController@updateUserImage');
    Route::post('update-user-timezone', 'UserController@updateUserTimezone');
    Route::post('change-password', 'UserController@changePassword');
    Route::post('update-intention-plan', 'UserController@updateintentionPlan');
    Route::post('update-two-way-auth', 'UserController@changeTwoWayAuth');
    Route::post('complete-intro-guide', 'UserController@completeIntro');
    Route::post('send-phone-otp', 'UserController@sendPhoneOtp');
    Route::delete('delete-profile', 'UserController@deleteProfile');
    Route::post('user-feedback', 'UserController@userFeedback');
    Route::post('/google/login/signup', 'UserController@googleLoginSignup');
    Route::get('profile-overview-result', 'UserController@profileOverviewResult');
    Route::get('summary-report', 'UserController@summaryReport');
    Route::get('version', 'UserController@getLatestVersion');

});

Route::get('timezone', 'UserController@getTimezone');
Route::post('forgot-password', 'UserController@forgotPassword');
