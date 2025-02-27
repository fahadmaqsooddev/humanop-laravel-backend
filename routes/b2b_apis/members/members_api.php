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
    
    Route::post('edit-member', 'MemberController@EditMember');
    Route::post('add-member', 'MemberController@addMember');
    Route::get('all-members','MemberController@AllMembers');
    Route::delete('delete-member','MemberController@DeleteMember');

});
