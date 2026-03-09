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

    Route::prefix('calendar/')->group(function () {

        Route::post('connect', 'CalendarIntegrationController@connect');
        Route::post('disconnect', 'CalendarIntegrationController@disconnect');
        Route::get('status', 'CalendarIntegrationController@status');

    };

});

Route::get('calendar/callback', 'CalendarIntegrationController@callback');
