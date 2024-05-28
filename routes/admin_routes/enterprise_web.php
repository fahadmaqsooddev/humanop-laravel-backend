<?php

use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'enterprise', 'middleware' => 'auth'], function () {

//    enterprise dashboard
    Route::view('/enterprise-dashboard', 'enterprise-dashboard/dashboard');
    Route::view('/enterprise-team-dashboard', 'enterprise-dashboard/team-dashboard');
    Route::get('/enterprise-roles-management', [RolesController::class, 'create']);
    Route::view('/enterprise-team-stats', 'enterprise-dashboard/team-stats');
    Route::view('/enterprise-strategies-development', 'enterprise-dashboard/strategies-development');
    Route::view('/enterprise-pages-account-settings', 'enterprise-dashboard/enterprise-setting');
    Route::view('/pages-account-billing', 'pages/account/billing');
    Route::view('/enterprise-billing', 'enterprise-dashboard/enterprise-billing');

});