<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Daily Sync API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Daily Sync API routes for the client app.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::group(['middleware' => ['checkUser']], function () {

    Route::prefix('daily-sync/')->group(function () {

        Route::get('status', 'DailySyncController@status');
        Route::get('archive', 'DailySyncController@archive');
        Route::post('start', 'DailySyncController@dailySyncStart');
        Route::post('response', 'DailySyncController@submitResponse');

    });
});
