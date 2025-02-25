<?php

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['checkUser']], function () {
 Route::post('add-member', 'B2BAuthController@addMember');
 
});