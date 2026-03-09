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

    Route::post('/calendar/connect', 'CalendarIntegrationController@connect');
    Route::post('/calendar/disconnect', 'CalendarIntegrationController@disconnect');
    Route::get('/calendar/status', 'CalendarIntegrationController@status');

});

Route::get('/calendar/callback', 'CalendarIntegrationController@callback');
