<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\AdminControllers\AdminController;
use Illuminate\Support\Facades\Route;

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

//Route::group(['middleware' => 'guest'], function () {
Route::get('/register', [RegisterController::class, 'create']);
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/session', [SessionController::class, 'store']);
Route::get('/login/forgot-password', [ChangePasswordController::class, 'create']);
Route::post('/forgot-password', [ChangePasswordController::class, 'sendEmail']);
Route::get('/reset-password/{token}', [ChangePasswordController::class, 'resetPass'])->name('password.reset');
Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
Route::get('/logout', [SessionController::class, 'destroy']);


Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {

//    admin dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin_dashboard');
//    Route::view('/dashboard-cms', 'dashboards/cms');
//    Route::view('/users', 'dashboards/all_users');
//    Route::view('/user-detail', 'dashboards/user_detail');
//    Route::view('/user-info', 'dashboards/user_info');
//    Route::view('/dashboard-hai-chat', 'dashboards/hai-chat');
//    Route::view('/grid', 'dashboards/grid');
//    Route::view('/answers', 'dashboards/answer');
//    Route::view('/pages-users-reports', 'pages/users/reports');
//    Route::view('/pages-users-new', 'pages/users/new-user');
//    Route::view('/pages-account-settings', 'dashboards/setting');
//    Route::view('/admin-projects', 'dashboards/admin_projects');

});
