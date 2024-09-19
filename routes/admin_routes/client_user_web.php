<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController\ClientController;
use App\Http\Controllers\ClientController\PricingController;
use App\Http\Controllers\ClientController\PaymentController;
use App\Http\Controllers\ClientController\QuestionController;
use App\Http\Controllers\ClientController\UserController;
use App\Http\Controllers\ClientController\ResourceController;
use App\Http\Controllers\ClientController\NetworkController;
use App\Http\Controllers\ClientController\BillingController;
use App\Http\Controllers\ClientController\SettingController;
use App\Http\Controllers\ClientController\CouponController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ClientController\MessageController;
use App\Http\Controllers\ClientController\StoryController;
use App\Http\Controllers\GoogleAuth\GoogleController;

/*
|--------------------------------------------------------------------------
| Client User Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::group(['prefix' => 'client', 'middleware' => ['isClient']], function () {

//    client dashboard
    Route::get('/dashboard', [ClientController::class, 'index'])->name('client_dashboard');

    //90 days plan test route
    Route::get('/ninety-day-plan', [ClientController::class, 'ninetyDayPlan']);
    //end here
    Route::get('/pricing', [PricingController::class, 'index'])->name('client_pricing');

    Route::get('/stripe-checkout', [PaymentController::class, 'showPaymentForm'])->name('stripe_checkout');
    Route::post('/stripe', [PaymentController::class, 'processPayment'])->name('process_payment');

    Route::get('/play', [QuestionController::class, 'testPlay'])->name('test_play');
    Route::get('/all-assessments', [QuestionController::class, 'allAssessment'])->name('all_assessment');

    Route::get('/user-detail/{id}', [UserController::class, 'userDetail'])->name('user_detail');
    Route::get('/user-info', [UserController::class, 'userInfo'])->name('user_info');
    Route::get('/user-profile-overview', [UserController::class, 'profileOverview'])->name('user_profile_overview');
    Route::get('/download-user-report/{id}', [UserController::class, 'downloadUserReport'])->name('download_user_report');
    Route::get('/user-grid/{id}', [UserController::class, 'grid'])->name('user_grid');
    Route::get('/user-report/{id}', [UserController::class, 'report'])->name('user_report');
    Route::get('/resource', [ResourceController::class, 'resource'])->name('resource');
    Route::get('/newsfeed', [NetworkController::class, 'network'])->name('newsfeed');
    Route::get('/billing', [BillingController::class, 'billing'])->name('billing');
    Route::get('/setting', [SettingController::class, 'setting'])->name('setting');
    Route::post('/delete-account', [SettingController::class, 'deleteAccount'])->name('delete_account');

    Route::post('/check-coupon', [CouponController::class, 'checkCoupon'])->name('check_coupon');

    Route::get('generate-pdf/{id}', [PDFController::class, 'generatePDF'])->name('generate_pdf');
    Route::get('generate-grid-pdf/{id}', [PDFController::class, 'generateGridPDF'])->name('generate_pdf');

    Route::get('payment-history', [PaymentController::class, 'PaymentHistory'])->name('payment_history');
    Route::get('messages', [MessageController::class, 'index'])->name('messages');

    Route::get('/stories', [StoryController::class, 'stories'])->name('stories');

    Route::post('/user-feedback', [UserController::class, 'userFeedback'])->name('user-feedback');
    Route::get('/follow', [NetworkController::class, 'followFollowing'])->name('follow');
    Route::get('/connections', [NetworkController::class, 'connection'])->name('connections');

    Route::post('/read-daily-tip', [\App\Http\Controllers\ClientController\ClientController::class,'readDailyTip'])->name('read-daily-tip');

});

//Route::get('/googleback', function (){
//
//    $user = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
//
//    dd($user);
//
//});
