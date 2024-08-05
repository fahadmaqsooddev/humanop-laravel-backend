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

Route::get('story-users', 'StoryController@storyUsers');
Route::post('create-story', 'StoryController@createStory');
Route::delete('delete-story', 'StoryController@deleteStory');
Route::get('user-stories','StoryController@userStories');
Route::post('story-viewed', 'StoryController@storyViewed');
Route::get('story-views', 'StoryController@storyViews');
