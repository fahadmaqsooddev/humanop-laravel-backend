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
use App\Http\Controllers\AdminControllers\PodcastController;
use App\Http\Controllers\HAIChat\ClientQueryController;
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

//Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/store-register', [RegisterController::class, 'store'])->name('store_user');
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

 Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function () {

 //    admin dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin_dashboard');

    Route::group(['middleware' => ['permission:users']], function () {
        Route::get('/users', [AdminController::class, 'allUsers'])->name('admin_all_users');
        Route::get('/user-detail/{id}', [AdminController::class, 'userDetail'])->name('admin_user_detail');
        Route::get('/user-info/{id}', [AdminController::class, 'userInfo'])->name('admin_user_info');
        Route::get('/user-grid/{id}', [AdminController::class, 'grid'])->name('admin_user_grid');
        Route::get('/user-report/{id}', [AdminController::class, 'report'])->name('admin_user_report');
        Route::get('/generate-pdf/{id}', [PDFController::class, 'generatePDF'])->name('admin_generate_pdf');
        Route::get('generate-grid-pdf/{id}', [PDFController::class, 'generateGridPDF'])->name('admin_generate_grid_pdf');
        Route::get('/user-answers/{id}', [AdminController::class, 'userAnswer'])->name('admin_user_answer');

    });

     Route::group(['middleware' => ['permission:abandonedAssessment']], function () {
         Route::get('/abandoned-assessment', [AdminController::class, 'abandonedAssessment'])->name('admin_abandoned_assessment');
     });

     Route::group(['middleware' => ['permission:deletedClient']], function () {
         Route::get('/deleted-clients', [AdminController::class,'deletedClients'])->name('deleted_clients');
     });

     Route::group(['middleware' => ['permission:clientQueries']], function () {
         Route::get('/client-queries', [ClientQueryController::class,'clientQuery'])->name('admin_client_queries');
     });

     Route::group(['middleware' => ['permission:approveQueries']], function () {
         Route::get('/approve-queries', [ClientQueryController::class,'approveQueries'])->name('admin_approve_queries');
     });

    Route::group(['middleware' => ['permission:cms']], function () {
        Route::get('/dashboard-cms', [AdminController::class, 'cms'])->name('admin_cms');
        Route::get('/codes', [CodeController::class, 'ManageCode'])->name('admin_manage_code');
        Route::get('/edit-code/{id}', [CodeController::class, 'editCode'])->name('admin_edit_manage_code');
        Route::get('/pages-users-reports', [AdminController::class, 'pagesUsersReports'])->name('admin_pages_users_reports');
        Route::get('/pages-users-new', [AdminController::class, 'pagesUsersNewUser'])->name('admin_pages_users_new_user');
        Route::get('/cms', [WebPagesController::class, 'webPages'])->name('admin_web_pages');
        Route::get('/cms/{id}', [WebPagesController::class, 'editWebPages'])->name('admin_edit_web_pages');
        Route::get('payment-history', [PaymentController::class, 'PaymentHistory'])->name('admin_payment_history');
        Route::get('feedback', [AdminController::class,'userFeedback'])->name('feedback');
        Route::get('podcast', [PodcastController::class,'podcast'])->name('podcast');

    });

    Route::group(['middleware' => ['permission:questions']], function () {
        Route::get('/questions', [QuestionController::class, 'allQuestions'])->name('admin_all_questions');
        Route::get('/edit-question/{id}', [QuestionController::class, 'editQuestions'])->name('admin_edit_questions');
    });

    Route::group(['middleware' => ['permission:chat']], function () {
        Route::get('/dashboard-hai-chat', [AdminController::class, 'haiChat'])->name('admin_hai_chat');
    });
    Route::group(['middleware' => ['permission:resources']], function () {
        Route::get('/resources', [ResourceController::class, 'resources'])->name('admin_resources');
    });

    Route::group(['middleware' => ['permission:projects']], function () {
        Route::get('/admin-projects', [AdminController::class, 'project'])->name('admin_projects');
    });

    Route::group(['middleware' => ['role:super admin']], function () {
        Route::get('/sub-admins', [AdminController::class, 'allAdmins'])->name('admin_all_sub_admins');
        Route::post('/stripe-settings/{id}', [AdminController::class, 'stripeSetting'])->name('stripe_setting');

        Route::get('/daily-tip', [TipController::class, 'index'])->name('admin_daily_tip');
        Route::get('/create-daily-tip', [TipController::class, 'create'])->name('admin_create_daily_tip');

        Route::get('/all-coupons', [CouponController::class, 'allCoupon'])->name('admin_all_coupon');


    });

    Route::get('/settings', [AdminController::class, 'setting'])->name('admin_setting');
});
