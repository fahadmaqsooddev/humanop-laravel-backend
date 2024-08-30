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

Route::get('ai-chat', 'ChatAiController@aiChat');
Route::post('ask-question', 'ChatAiController@askQuestion');
Route::post('like-dislike-ai-reply', 'ChatAiController@likeDislikeAiReply');
Route::post('client-query', 'ChatAiController@clientQuery');
Route::get('client-query-answer', 'ChatAiController@clientQueryAnswer');
Route::get('chat_history', 'ChatAiController@chatHistory');
