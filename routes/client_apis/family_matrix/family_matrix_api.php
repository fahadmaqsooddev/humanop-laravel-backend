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

    Route::prefix('v4/family-matrix/')->group(function () {

        Route::get('analyze', 'App\Http\Controllers\Api\ClientController\FamilyMatrix\FamilyMatrixController@familyMatrixAnalyze');

    });

});
