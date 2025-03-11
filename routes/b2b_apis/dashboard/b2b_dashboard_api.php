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

    Route::get('candidate-optimization-and-core-state', 'B2BDashboardController@candidateOptimizationAndCoreState');
    Route::post('store-note','B2BDashboardController@StoreNotes');
    Route::get('get-note','B2BDashboardController@getNote');


});

Route::get('all-intentions','B2BDashboardController@AllIntentions');
