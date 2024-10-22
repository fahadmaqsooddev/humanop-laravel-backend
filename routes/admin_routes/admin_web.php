<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\AdminControllers\AdminController;
use App\Http\Controllers\AdminControllers\QuestionController;
use App\Http\Controllers\AdminControllers\CodeController;
use App\Http\Controllers\AdminControllers\WebPagesController;
use App\Http\Controllers\AdminControllers\ResourceController;
use App\Http\Controllers\AdminControllers\CouponController;
use App\Http\Controllers\AdminControllers\IntentionPlanController;
use App\Http\Controllers\AdminControllers\DailyTipController;
use App\Http\Controllers\AdminControllers\PaymentController;
use App\Http\Controllers\AdminControllers\PodcastController;
use App\Http\Controllers\AdminControllers\InformationController;
use App\Http\Controllers\HAIChat\ClientQueryController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Practitioner\PractitionerController;

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
    Route::get('/email-verify', [RegisterController::class, 'emailVerify'])->name('email_verify');
    Route::get('/call-back-register', [RegisterController::class, 'callBackRegistration'])->name('call_back_registration');
    Route::get('/resend-email-verification', [RegisterController::class, 'resendEmailVerification'])->name('resend_email_verification');
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/session', [SessionController::class, 'store']);
    Route::post('/forgot-password', [ChangePasswordController::class, 'sendEmail']);
    Route::get('/login/forgot-password', [ChangePasswordController::class, 'create']);
//    Route::post('/forgot-password', [ChangePasswordController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ChangePasswordController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
    Route::get('/logout', [SessionController::class, 'destroy']);
    Route::get('/', [SessionController::class, 'create'])->name('login');

//});

$prefix = request()->segment(1) === 'admin' || request()->segment(1) === 'practitioner' ? request()->segment(1) : "admin";

$prefix = $prefix === 'admin' ? str_contains(request()->url(),'/client.') ? 'practitioner' : $prefix : $prefix;

Route::group(['prefix' => $prefix, 'middleware' => ['isAdmin']], function () {

    //    admin dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin_dashboard');

    Route::get('/intro-assessment', [QuestionController::class, 'introAssessment'])->name('practitioner_intro_assessment');
    Route::get('/play', [QuestionController::class, 'testPlay'])->name('admin_test_play');
    Route::get('/all-assessments', [QuestionController::class, 'allAssessment'])->name('admin_all_assessment');
    Route::get('/practitioner-profile-overview/{id?}', [AdminController::class, 'profileOverview'])->name('practitioner_profile_overview');
    Route::get('/practitioner-grid/{id}', [AdminController::class, 'grid'])->name('practitioner_grid');
    Route::get('/download-practitioner-report/{id}', [AdminController::class, 'downloadUserReport'])->name('download_practitioner_report');

    Route::group(['middleware' => ['permission:users']], function () {
        Route::get('/users', [AdminController::class, 'allUsers'])->name('admin_all_users');
        Route::get('/user-detail/{id}', [AdminController::class, 'userDetail'])->name('admin_user_detail');
        Route::get('/user-info/{id}', [AdminController::class, 'userInfo'])->name('admin_user_info');
        Route::get('/user-grid/{id}', [AdminController::class, 'grid'])->name('admin_user_grid');
        Route::get('/user-report/{id}', [AdminController::class, 'report'])->name('admin_user_report');
        Route::get('/generate-pdf/{id}', [PDFController::class, 'generatePDF'])->name('admin_generate_pdf');
        Route::get('generate-grid-pdf/{id}', [PDFController::class, 'generateGridPDF'])->name('admin_generate_grid_pdf');
        Route::get('/user-answers/{id}', [AdminController::class, 'userAnswer'])->name('admin_user_answer');
        Route::get('/user-profile-overview/{id?}', [AdminController::class, 'profileOverview'])->name('admin_profile_overview');
        Route::get('/download-user-report/{id}', [AdminController::class, 'downloadUserReport'])->name('download_user_report');

    });

    Route::group(['middleware' => ['permission:abandonedAssessment']], function () {
        Route::get('/abandoned-assessment', [AdminController::class, 'abandonedAssessment'])->name('admin_abandoned_assessment');
        Route::get('/assessments', [AdminController::class, 'assessments'])->name('assessments');
    });

    Route::group(['middleware' => ['permission:practitioner']], function () {
        Route::get('/practitioners', [PractitionerController::class, 'allPractitioners'])->name('admin_all_practitioners');
    });

    Route::group(['middleware' => ['permission:deletedClient']], function () {
        Route::get('/deleted-clients', [AdminController::class, 'deletedClients'])->name('deleted_clients');
    });

    Route::group(['middleware' => ['permission:clientQueries']], function () {
        Route::get('/client-queries', [ClientQueryController::class, 'clientQuery'])->name('admin_client_queries');
    });

    Route::group(['middleware' => ['permission:approveQueries']], function () {
        Route::get('/approve-queries', [ClientQueryController::class, 'approveQueries'])->name('admin_approve_queries');
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
        Route::get('feedback', [AdminController::class, 'userFeedback'])->name('feedback');
        Route::get('podcast', [PodcastController::class, 'podcast'])->name('podcast');
        Route::get('/all-coupons', [CouponController::class, 'allCoupon'])->name('admin_all_coupon');
        Route::get('/information-icon', [InformationController::class, 'getInfo'])->name('admin_get_info');

        Route::get('/all-intention-plans', [IntentionPlanController::class, 'allIntentionPlan'])->name('admin_all_intention_plan');

        Route::get('/all-daily-tips', [DailyTipController::class, 'allDailyTip'])->name('admin_all_daily_tip');
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
        Route::get('/create-resources', [ResourceController::class, 'createrResources'])->name('admin_create_resources');
    });

    Route::group(['middleware' => ['permission:projects']], function () {
        Route::get('/admin-projects', [AdminController::class, 'project'])->name('admin_projects');
    });

    Route::group(['middleware' => ['role:super admin']], function () {
        Route::get('/sub-admins', [AdminController::class, 'allAdmins'])->name('admin_all_sub_admins');
        Route::post('/stripe-settings/{id}', [AdminController::class, 'stripeSetting'])->name('stripe_setting');

    });

    Route::get('/login-back-to-admin', [SessionController::class,'loginBackToAdmin'])->name('login_back_to_admin');

    Route::get('/settings', [AdminController::class, 'setting'])->name('admin_setting');
});
