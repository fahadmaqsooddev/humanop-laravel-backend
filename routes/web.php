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
    Route::view('/pages-users-reports', 'pages/users/reports');
    Route::view('/pages-users-new', 'pages/users/new-user');
    Route::view('/pages-account-settings', 'dashboards/setting');
    Route::view('/admin-projects', 'dashboards/admin_projects');


//    client dashboard
    Route::view('/client-dashboard', 'client-dashboard/client-dashboard');
    Route::view('/client-resource', 'client-dashboard/client-resource');
    Route::view('/client-dashboard-result', 'client-dashboard/video');
    Route::view('/client-user-detail', 'client-dashboard/client_user_detail');
    Route::view('/client-grid', 'client-dashboard/client_grid');
    Route::view('/client-answers', 'client-dashboard/client_answer');
    Route::view('/client-user-info', 'client-dashboard/client_user_info');
    Route::view('/client-pages-account-settings', 'client-dashboard/client-setting');
    Route::view('/client-billing', 'client-dashboard/client_billing');
    Route::view('/client-human-network', 'client-dashboard/network');

//    practitioner dashboard
    Route::view('/practitioner-dashboard', 'practitioner-dashboard/dashboard');
    Route::view('/practitioner-projects', 'practitioner-dashboard/projects');
    Route::view('/practitioner-new-user', 'practitioner-dashboard/new-user');
    Route::view('/practitioner-total-sales', 'practitioner-dashboard/total-sales');
    Route::view('/practitioner-pages-account-settings', 'practitioner-dashboard/practitioner-setting');
    Route::view('/practitioner-user-detail', 'practitioner-dashboard/practitioner_user_detail');
    Route::view('/pages-account-billing', 'pages/account/billing');
    Route::view('/practitioner-billing', 'practitioner-dashboard/practitioner-billing');

//    enterprise dashboard
    Route::get('/enterprise-roles-management', [RolesController::class, 'create']);
    Route::get('/enterprise-tags-management', [TagsController::class, 'create']);
    Route::view('/enterprise-team-stats', 'enterprise-dashboard/team-stats');
    Route::view('/enterprise-strategies-development', 'enterprise-dashboard/strategies-development');
    Route::view('/enterprise-pages-account-settings', 'enterprise-dashboard/enterprise-setting');
    Route::view('/pages-account-billing', 'pages/account/billing');
    Route::view('/enterprise-billing', 'enterprise-dashboard/enterprise-billing');

//    user profile
    Route::get('/profile-user', [UserProfileController::class, 'create']);
    Route::post('/save-user-profile', [UserProfileController::class, 'store']);

    Route::get('/logout', [SessionController::class, 'destroy']);
    Route::view('/login', 'dashboards/default')->name('sign-up');

    Route::view('/pages-account-billing', 'pages/account/billing');
    Route::view('/pages-account-invoice', 'pages/account/invoice');
    Route::view('/pages-account-security', 'pages/account/security');





    Route::view('/pages-profile-overview', 'pages/profile/overview');
    Route::view('/pages-profile-projects', 'pages/profile/projects');

    Route::view('/pages-users-reports', 'pages/users/reports');
    Route::view('/pages-users-new', 'pages/users/new-user');

    Route::view('/pages-account-settings', 'pages/account/settings');
    Route::view('/pages-account-billing', 'pages/account/billing');
    Route::view('/pages-account-invoice', 'pages/account/invoice');
    Route::view('/pages-account-security', 'pages/account/security');

    Route::view('/pages-projects-general', 'pages/projects/general');
    Route::view('/pages-projects-new-project', 'pages/projects/new-project');
    Route::view('/pages-projects-timeline', 'pages/projects/timeline');

    Route::view('/pages-charts', 'pages/charts');
    Route::view('/pages-notifications', 'pages/notifications');
    Route::view('/pages-pricing', 'pages/pricing-page');
    Route::view('/pages-rtl', 'pages/rtl-page');
    Route::view('/pages-sweet-alerts', 'pages/sweet-alerts');
    Route::view('/pages-widgets', 'pages/widgets');

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
