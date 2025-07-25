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
    Route::post('update-personal-information', 'UserController@updatePersonalInformation');
    Route::post('update-profile', 'UserController@updateProfile');
    Route::post('update-user-image', 'UserController@updateUserImage');
    Route::post('update-user-timezone', 'UserController@updateUserTimezone');
    Route::post('change-password', 'UserController@changePassword');
    Route::post('update-intention-plan', 'UserController@updateintentionPlan');
    Route::post('update-two-way-auth', 'UserController@changeTwoWayAuth');
    Route::post('complete-intro-guide', 'UserController@completeIntro');
    Route::delete('delete-profile', 'UserController@deleteProfile');
    Route::post('user-feedback', 'UserController@userFeedback');
    Route::post('/google/login/signup', 'UserController@googleLoginSignup');
    Route::get('profile-overview-result', 'UserController@profileOverviewResult');
    Route::get('summary-report', 'UserController@summaryReport');
    Route::get('version', 'UserController@getLatestVersion');
    Route::get('referral-credits', 'UserController@referralCredits');
    Route::post('check-prompt-notification','UserController@updatePromptNotification');
    Route::post('change-profile-public-private','UserController@profilePublicOrPrivate');
    Route::post('change-hai-access','UserController@haiAccess');

    Route::get('get-associated-companies', 'UserController@getAssociatedCompanies');
    Route::post('share-not-share-data-with-associated-companies', 'UserController@shareNotShareDataAssociatedCompanies');
    Route::post('remove-company', 'UserController@removeCompany');


});

Route::get('timezone', 'UserController@getTimezone');
Route::post('reset-password', 'UserController@forgotPassword');
