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

    Route::get('posts', 'PostController@posts');
    Route::post('create-post', 'PostController@createPost');
    Route::post('edit-post', 'PostController@editPost');
    Route::get('post', 'PostController@post');
    Route::delete('delete-post', 'PostController@deletePost');
    Route::post('like-unlike-post', 'PostController@likeUnLikePost');
    Route::post('share-post', 'PostController@sharePost');
    Route::get('comments', 'PostController@comments');
    Route::get('comment', 'PostController@comment');
    Route::post('create-comment', 'PostController@createComment');
    Route::post('edit-comment', 'PostController@editComment');
    Route::delete('delete-comment', 'PostController@deleteComment');
    Route::post('like-unlike-comment', 'PostController@likeUnLikeComment');

});
