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

    Route::get('list-admin-role', 'RoleController@listAdminRole');
    Route::get('list-role-tasks', 'RoleController@listRoleTasks');

});
