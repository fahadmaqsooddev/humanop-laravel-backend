<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UsersController;
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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect('dashboard-default');
    });

//    admin dashboard
    Route::view('/dashboard-default', 'dashboards/default');
    Route::view('/dashboard-cms', 'dashboards/cms');
    Route::view('/users', 'dashboards/all_users');
    Route::view('/user-detail', 'dashboards/user_detail');
    Route::view('/user-info', 'dashboards/user_info');
    Route::view('/dashboard-hai-chat', 'dashboards/hai-chat');
    Route::view('/grid', 'dashboards/grid');
    Route::view('/answers', 'dashboards/answer');
    Route::view('/admin-projects', 'dashboards/admin_projects');


//    client dashboard
    Route::view('/client-dashboard', 'client-dashboard/client-dashboard');
    Route::view('/client-dashboard-result', 'client-dashboard/video');
    Route::view('/client-user-detail', 'client-dashboard/client_user_detail');
    Route::view('/client-grid', 'client-dashboard/client_grid');
    Route::view('/client-answers', 'client-dashboard/client_answer');
    Route::view('/client-user-info', 'client-dashboard/client_user_info');



//    practitioner dashboard
    Route::view('/practitioner-dashboard', 'practitioner-dashboard/dashboard');
    Route::view('/practitioner-projects', 'practitioner-dashboard/projects');
    Route::view('/practitioner-new-user', 'practitioner-dashboard/new-user');
    Route::view('/practitioner-total-sales', 'practitioner-dashboard/total-sales');
    Route::view('/practitioner-user-detail', 'practitioner-dashboard/practitioner_user_detail');


//    enterprise dashboard
    Route::get('/enterprise-roles-management', [RolesController::class, 'create']);
    Route::get('/enterprise-tags-management', [TagsController::class, 'create']);
    Route::view('/enterprise-team-stats', 'enterprise-dashboard/team-stats');
    Route::view('/enterprise-strategies-development', 'enterprise-dashboard/strategies-development');

//    user profile
    Route::get('/profile-user', [UserProfileController::class, 'create']);
    Route::post('/save-user-profile', [UserProfileController::class, 'store']);

    Route::get('/logout', [SessionController::class, 'destroy']);
    Route::view('/login', 'dashboards/default')->name('sign-up');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/session', [SessionController::class, 'store']);
    Route::get('/login/forgot-password', [ChangePasswordController::class, 'create']);
    Route::post('/forgot-password', [ChangePasswordController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ChangePasswordController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
    Route::get('/', function () {
        return redirect('/login');
    });
});
