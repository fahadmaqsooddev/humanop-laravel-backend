<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{slug?}/{slug2?}/login', [SessionController::class, 'practitionerLogin'])->name('login');
Route::post('/practitionerSession', [SessionController::class, 'practitionerStore']);


Route::group(['prefix' => '{slug?}/{slug2?}', 'middleware' => 'auth'], function () {

//    practitioner dashboard
    Route::view('/practitioner-dashboard', 'practitioner-dashboard/dashboard');
    Route::view('/practitioner-projects', 'practitioner-dashboard/projects');
    Route::view('/practitioner-new-user', 'practitioner-dashboard/new-user');
    Route::view('/practitioner-total-sales', 'practitioner-dashboard/total-sales');
    Route::view('/practitioner-pages-account-settings', 'practitioner-dashboard/practitioner-setting');
    Route::view('/practitioner-user-detail', 'practitioner-dashboard/practitioner_user_detail');
    Route::view('/pages-account-billing', 'pages/account/billing');
    Route::view('/practitioner-billing', 'practitioner-dashboard/practitioner-billing');

});
