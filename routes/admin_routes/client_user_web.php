<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController\ClientController;
use App\Http\Controllers\ClientController\PricingController;
use App\Http\Controllers\ClientController\PaymentController;
use App\Http\Controllers\ClientController\QuestionController;

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

Route::group(['prefix' => 'client'], function () {

//    client dashboard
    Route::get('/dashboard', [ClientController::class, 'index'])->name('client_dashboard');

    Route::get('/pricing-page', [PricingController::class, 'index'])->name('pricing');

    Route::get('/stripe-checkout', [PaymentController::class, 'showPaymentForm'])->name('stripe_checkout');
    Route::post('/stripe', [PaymentController::class, 'processPayment'])->name('process_payment');

    Route::get('/play', [QuestionController::class, 'testPlay'])->name('test_play');

    Route::view('/client-resource', 'client-dashboard/client-resource');
    Route::view('/client-dashboard-result', 'client-dashboard/video');
    Route::view('/client-user-detail', 'client-dashboard/client_user_detail');
    Route::view('/client-grid', 'client-dashboard/client_grid');
    Route::view('/client-answers', 'client-dashboard/client_answer');
    Route::view('/client-user-info', 'client-dashboard/client_user_info');
    Route::view('/client-pages-account-settings', 'client-dashboard/client-setting');
    Route::view('/client-billing', 'client-dashboard/client_billing');
    Route::view('/client-human-network', 'client-dashboard/network');


});
