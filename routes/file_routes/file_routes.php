<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\AdminControllers\AdminController;
use App\Http\Controllers\AdminControllers\QuestionController;
use App\Http\Controllers\AdminControllers\CodeController;
use App\Http\Controllers\AdminControllers\WebPagesController;
use App\Http\Controllers\AdminControllers\ResourceController;
use App\Http\Controllers\AdminControllers\TipController;
use App\Http\Controllers\AdminControllers\CouponController;
use App\Http\Controllers\AdminControllers\PaymentController;
use App\Http\Controllers\PDFController;
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
