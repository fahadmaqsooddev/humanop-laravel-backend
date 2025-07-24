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

    Route::get('get-hp', 'GamificationController@getHp');
    Route::get('login-streaks', 'GamificationController@loginStreaks');
    Route::get('current-user-badge', 'GamificationController@currentUserBadge');
    Route::get('current-user-medal', 'GamificationController@currentUserMedal');
    Route::post('complete-watch-video', 'GamificationController@completeWatchVideo');
    Route::get('get-badges-and-medals', 'GamificationController@getBadgesAndMedals');
    Route::get('get-performance-level', 'GamificationController@getPerformanceLevel');
    Route::post('purchase-hai-credits-from-hp', 'GamificationController@purchaseHaiCreditsFromHp');

});

