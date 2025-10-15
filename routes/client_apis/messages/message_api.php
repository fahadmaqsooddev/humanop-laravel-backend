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


    Route::get('all-threads', 'ThreadController@allThreads');
    Route::get('show-threads', 'ThreadController@showTHreads');
    Route::post('create-group-chat', 'ThreadController@createGroupChat');
    Route::post('edit-group', 'ThreadController@editGroup');
    Route::post('add-users-in-group', 'ThreadController@addUsersInGroup');
    Route::delete('remove-user-in-group', 'ThreadController@removeUserInGroup');
    Route::post('direct-chats', 'DirectController@directChat');
    Route::post('store-messages', 'MessageController@storeMessages');
    Route::get('all-messages', 'MessageController@allMessages');
    Route::post('change-role', 'ThreadController@setRole');
    Route::post('changed-group-filter', 'ThreadController@changedGroupFilter');

    Route::post('/threads/{messageThread}/read', 'MessageController@markRead');

});
