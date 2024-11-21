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

    Route::get('chats', 'MessageController@chats');
    Route::post('send-message', 'MessageController@sendMessage');
    Route::get('messages', 'MessageController@messages');
    Route::delete('delete-chat', 'MessageController@deleteChat');

});
