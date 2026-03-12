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

    Route::post('healthkit/samples', 'HealthKitController@ingestSamples');
    Route::post('healthkit/locations', 'HealthKitController@ingestLocations');

    Route::post('boost-sessions/start', 'BoostSessionController@start');
    Route::post('boost-sessions/end', 'BoostSessionController@end');

    Route::get('energy-shield', 'EnergyShieldController@show');
    Route::get('events', 'EventController@index');
    Route::patch('events/{id}/acknowledge', 'EventController@acknowledge');

});
