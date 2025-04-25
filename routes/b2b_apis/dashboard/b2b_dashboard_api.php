<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\B2BControllers\B2BApi\B2BNotificationController;


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
    Route::get('b2b-optimization-and-core-state', 'B2BDashboardController@b2bOptimizationAndCoreState');
    Route::post('store-note','B2BDashboardController@StoreNotes');
    Route::get('get-note','B2BDashboardController@getNote');
    Route::get('all-b2b-notification', 'B2BNotificationController@B2Bnotifications');

    Route::post('b2b-ask-question', 'B2BHaiController@askQuestion');

});
