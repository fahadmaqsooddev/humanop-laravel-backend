<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can member API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['checkUser']], function () {

    Route::get('all-companies', 'B2BCompanyController@allCompanies');
    // Route::get('candidate-selected-companies', 'B2BCompanyController@candidateSelectedCompanies');


});
