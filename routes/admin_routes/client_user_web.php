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

Route::group(['prefix' => 'client', 'middleware' => ['isClient']], function () {

//    client dashboard
    Route::get('/dashboard', [ClientController::class, 'index'])->name('client_dashboard');

    Route::get('/pricing-page', [PricingController::class, 'index'])->name('pricing');

    Route::get('/stripe-checkout', [PaymentController::class, 'showPaymentForm'])->name('stripe_checkout');
    Route::post('/stripe', [PaymentController::class, 'processPayment'])->name('process_payment');

    Route::get('/play', [QuestionController::class, 'testPlay'])->name('test_play');
    Route::get('/all-assessments', [QuestionController::class, 'allAssessment'])->name('all_assessment');

    Route::get('/user-detail/{id}', [UserController::class, 'userDetail'])->name('user_detail');
    Route::get('/user-info', [UserController::class, 'userInfo'])->name('user_info');
    Route::get('/user-answers/{id}', [UserController::class, 'answers'])->name('user_answers');
    Route::get('/user-grid/{id}', [UserController::class, 'grid'])->name('user_grid');
    Route::get('/resource', [ResourceController::class, 'resource'])->name('resource');
    Route::get('/human-network', [NetworkController::class, 'network'])->name('human_network');
    Route::get('/billing', [BillingController::class, 'billing'])->name('billing');
    Route::get('/setting', [SettingController::class, 'setting'])->name('setting');

    Route::post('/check-coupon',[CouponController::class, 'checkCoupon'])->name('check_coupon');
});
