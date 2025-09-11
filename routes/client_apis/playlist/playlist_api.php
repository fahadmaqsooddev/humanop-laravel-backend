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

    Route::get('my-playlist', 'PlaylistController@myPlaylists');
    Route::post('update-my-playlist', 'PlaylistController@updateMyPlaylists');
    Route::delete('delete-my-playlist', 'PlaylistController@deleteMyPlaylists');
    Route::post('new-playlist', 'PlaylistController@newPlaylist');
    Route::post('add-my-playlist', 'PlaylistLogController@addMyPlaylist');
    Route::delete('delete-my-playlist-item', 'PlaylistLogController@deleteMyPlaylistItem');

    Route::get('sound-track-lists', 'SoundTrackController@soundTrackLists');
    Route::get('recommended-sound-track-lists', 'SoundTrackController@recommendedSoundTrackLists');


});
