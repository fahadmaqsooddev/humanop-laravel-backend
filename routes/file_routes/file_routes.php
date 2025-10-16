<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('files/{hash}/{name}', 'UploadsController@get_file');
Route::get('thumbnails/{hash}/{name}', 'UploadsController@get_file_thumbnail');
Route::get('videos/{hash}/{name}', 'UploadsController@get_video');
Route::get('audios/{hash}/{name}', 'UploadsController@get_audio');
Route::get('documents/{hash}/{name}', 'UploadsController@get_document');
