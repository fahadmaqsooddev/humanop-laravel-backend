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

Route::group(['middleware' => ['checkUser','assessment']], function () {

    Route::get('/resource-urls', 'LibraryResourceController@resourceUrls');
    Route::get('/resource-categories', 'LibraryResourceController@resourceCategories');
    Route::post('library-resource-item-checkout', 'LibraryResourceController@libraryResourceItemCheckout');


    Route::get('media-player-categories', 'LibraryResourceController@mediaPlayerCategories');
    Route::get('media-player-resources', 'LibraryResourceController@mediaPlayerResources');


});
