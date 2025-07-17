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

    Route::post('follow-unfollow', 'HumanNetworkController@followUnfollow');
    Route::get('followers', 'HumanNetworkController@followers');
    Route::get('following', 'HumanNetworkController@following');
    Route::post('connect-unconnect', 'HumanNetworkController@connectUnconnect');
    Route::get('users', 'HumanNetworkController@users');
    Route::get('connection-requests', 'HumanNetworkController@connectionRequests');
    Route::get('connections', 'HumanNetworkController@connections');
    Route::get('style-feature-codes', 'HumanNetworkController@styleFeatureCodes');
    Route::get('alchemy-codes', 'HumanNetworkController@alchemyCodes');
    Route::get('network-tutorials', 'HumanNetworkController@networkTutorials');
    Route::get('core-stats-comparison-between-users', 'HumanNetworkController@coreStatsComparisonBetweenUsers');

});
