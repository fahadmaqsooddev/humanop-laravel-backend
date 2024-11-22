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

    Route::get('daily_tip', 'DashboardController@dailyTip');
    Route::get('hai_chat_status', 'DashboardController@haiChatStatus');
    Route::get('podcast', 'DashboardController@latestPodcast');
    Route::get('core-stats', 'DashboardController@coreStats');
    Route::post('daily-tip-read', 'DashboardController@dailyTipRead');
    Route::get('action-plan', 'DashboardController@actionPlan');
    Route::get('information-icon', 'DashboardController@informationIcon');
    Route::get('optional-trait', 'DashboardController@optionalTrait');

});
