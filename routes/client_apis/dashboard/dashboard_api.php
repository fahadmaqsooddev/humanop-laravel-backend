<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can dashboard API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['checkUser']], function () {

    Route::get('daily_tip', 'DashboardController@dailyTip');
    Route::post('favorite-daily-tip', 'DashboardController@favoriteDailyTip');
    Route::get('get-favorite-daily-tip', 'DashboardController@getFavoriteDailyTip');
    Route::get('podcast', 'DashboardController@getPodcasts');
    Route::get('core-stats', 'DashboardController@coreStats');
    Route::post('daily-tip-read', 'DashboardController@dailyTipRead');
    Route::get('action-plan', 'DashboardController@actionPlan');
    Route::get('information-icon', 'DashboardController@informationIcon');
    Route::get('optimal-trait', 'DashboardController@optimalTrait');
    Route::get('assessment-walkthrough','DashboardController@getWalkThrough');
    Route::post('complete-assessment-walkthrough','DashboardController@completeWalkThrough');
    Route::post('complete-tutorial','DashboardController@completeTutorial');
    Route::get('check-shared-data','DashboardController@CheckShareData');
    Route::post('shared-data','DashboardController@sharedData');
    Route::post('not-shared-data','DashboardController@notSharedData');
    Route::get('all-push-notification','DashboardController@getPushNotification');
    Route::post('push-notification-allow-or-not-allow','DashboardController@pushNotification');
    Route::get('all-versions','DashboardController@getVersions');
    Route::get('version-update','DashboardController@versionUpdate');
    Route::get('latest-library-resource','DashboardController@topLibraryResourcses');
    Route::get('announcement-news','DashboardController@announcementNews');
    Route::get('recent-player','DashboardController@recentPlayer');
    Route::post('add-recent-player','DashboardController@addRecentPlayer');


    Route::get('all-companies', 'DashboardController@allCompanies');
    Route::get('check-future-consideration-share-data','DashboardController@checkFutureConsiderationShareData');
    Route::post('future-consideration-share-data','DashboardController@futureConsiderationShareData');
    Route::post('future-consideration-not-share-data','DashboardController@futureConsiderationNotShareData');

   Route::get('hai-chat-status', 'DashboardController@haiChatStatus');

});
