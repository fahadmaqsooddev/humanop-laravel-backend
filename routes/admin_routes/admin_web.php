<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\AdminControllers\AdminController;
use App\Http\Controllers\AdminControllers\QuestionController;
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

    Route::get('/', function () {
        return redirect('/login');
    });
//});

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {

//    admin dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin_dashboard');
    Route::get('/dashboard-cms', [AdminController::class,'cms'])->name('admin_cms');
    Route::get('/users', [AdminController::class,'allUsers'])->name('admin_all_users');
    Route::get('/user-detail/{id}', [AdminController::class,'userDetail'])->name('admin_user_detail');
    Route::get('/user-info/{id}', [AdminController::class,'userInfo'])->name('admin_user_info');
    Route::get('/dashboard-hai-chat', [AdminController::class,'haiChat'])->name('admin_hai_chat');
    Route::get('/grid', [AdminController::class,'grid'])->name('admin_grid');
    Route::get('/answers', [AdminController::class,'answer'])->name('admin_answer');
    Route::get('/pages-users-reports', [AdminController::class,'pagesUsersReports'])->name('admin_pages_users_reports');
    Route::get('/pages-users-new', [AdminController::class,'pagesUsersNewUser'])->name('admin_pages_users_new_user');
    Route::get('/pages-account-settings', [AdminController::class,'setting'])->name('admin_setting');
    Route::post('/stripe-settings/{id}', [AdminController::class,'stripeSetting'])->name('stripe_setting');
    Route::get('/admin-projects', [AdminController::class,'project'])->name('admin_projects');

    Route::get('/questions', [QuestionController::class,'allQuestions'])->name('admin_all_questions');

});
