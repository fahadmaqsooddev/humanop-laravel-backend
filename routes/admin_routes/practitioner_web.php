<?php

use App\Http\Controllers\ClientController\ClientController;
use App\Http\Controllers\ClientController\PricingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ClientController\PaymentController;
use App\Http\Controllers\ClientController\QuestionController;
use App\Http\Controllers\ClientController\UserController;
use App\Http\Controllers\ClientController\ResourceController;
use App\Http\Controllers\ClientController\NetworkController;
use App\Http\Controllers\ClientController\SettingController;
use App\Http\Controllers\ClientController\CouponController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ClientController\MessageController;
use App\Http\Controllers\GoogleAuth\GoogleController;

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


Route::get('/{slug?}/{slug2?}/login', [SessionController::class, 'practitionerLogin'])->name('practitioner_login');
Route::post('/{slug?}/{slug2?}/login-client-to-practitioner', [SessionController::class, 'loginClientToPractitioner'])->name('login_client_to_practitioner');
Route::get('/{slug?}/{slug2?}/register', [RegisterController::class, 'practitionerRegister'])->name('practitioner_register');
Route::post('/{slug?}/{slug2?}/register-client-to-practitioner', [RegisterController::class, 'registerClientToPractitioner'])->name('register_client_to_practitioner');
Route::get('/{slug?}/{slug2?}/logout', [SessionController::class, 'destroyPractitioner']);
Route::get('/{slug?}/{slug2?}/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/{slug?}/{slug2?}/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/{slug?}/{slug2?}/email-verify', [RegisterController::class, 'practitionerEmailVerify'])->name('practitioner_email_verify');
Route::get('/{slug?}/{slug2?}/check-email/{id}', [RegisterController::class, 'practitionerCheckEmail'])->name('practitioner_check_email');

Route::group(['prefix' => '{slug}/{slug2}', 'middleware' => 'isPractitioner'], function () {

//    practitioner dashboard
    Route::get('/dashboard', [ClientController::class, 'index'])->name('practitioner_dashboard');

    //90 days plan test route
    Route::get('/ninety-day-plan', [ClientController::class, 'ninetyDayPlan']);
    //end here
    Route::get('/pricing', [PricingController::class, 'index'])->name('practitioner_client_pricing');

//    Route::get('/stripe-checkout', [PaymentController::class, 'showPaymentForm'])->name('stripe_checkout');
//    Route::post('/stripe', [PaymentController::class, 'processPayment'])->name('process_payment');

    Route::get('/intro-assessment', [QuestionController::class, 'introAssessment'])->name('practitioner_client_intro_assessment');
    Route::get('/play', [QuestionController::class, 'testPlay'])->name('practitioner_test_play');
    Route::get('/all-assessments', [QuestionController::class, 'allAssessment'])->name('practitioner_all_assessment');

    Route::get('/user-detail/{id}', [UserController::class, 'userDetail'])->name('practitioner_user_detail');
    Route::get('/user-info', [UserController::class, 'userInfo'])->name('practitioner_user_info');
    Route::get('/practitioner-client-profile-overview/{id}', [UserController::class, 'profileOverview'])->name('practitioner_user_profile_overview');
    Route::get('/download-user-report/{id}', [UserController::class, 'downloadUserReport'])->name('practitioner_download_user_report');
    Route::get('/user-grid/{id}', [UserController::class, 'grid'])->name('practitioner_user_grid');
    Route::get('/user-report/{id}', [UserController::class, 'report'])->name('practitioner_user_report');
    Route::get('/resource', [ResourceController::class, 'resource'])->name('practitioner_resource');
    Route::get('/newsfeed', [NetworkController::class, 'network'])->name('practitioner_newsfeed');
    Route::get('/setting', [SettingController::class, 'setting'])->name('practitioner_setting');
    Route::post('/delete-account', [SettingController::class, 'deleteAccount'])->name('practitioner_delete_account');

    Route::post('/check-coupon', [CouponController::class, 'checkCoupon'])->name('practitioner_check_coupon');

    Route::get('generate-pdf/{id}', [PDFController::class, 'generatePDF'])->name('practitioner_generate_pdf');
    Route::get('generate-grid-pdf/{id}', [PDFController::class, 'generateGridPDF'])->name('generate_pdf');

    Route::get('messages', [MessageController::class, 'index'])->name('practitioner_messages');

    Route::get('/stories', [\App\Http\Controllers\ClientController\StoryController::class, 'stories'])->name('practitioner_stories');

    Route::post('/user-feedback', [UserController::class, 'userFeedback'])->name('practitioner-user-feedback');
    Route::get('/follow', [NetworkController::class, 'followFollowing'])->name('practitioner_follow');
    Route::get('/connections', [NetworkController::class, 'connection'])->name('practitioner_connections');

    Route::post('/read-daily-tip', [\App\Http\Controllers\ClientController\ClientController::class,'readDailyTip'])->name('practitioner_read-daily-tip');

    Route::get('login-back-to-admin', [\App\Http\Controllers\SessionController::class,'loginBackToAdmin'])->name('practitioner_login_back_to_admin');


});
