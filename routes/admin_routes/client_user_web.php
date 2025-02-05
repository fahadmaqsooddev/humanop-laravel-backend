<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController\ClientController;
use App\Http\Controllers\ClientController\PricingController;
use App\Http\Controllers\ClientController\PaymentController;
use App\Http\Controllers\ClientController\QuestionController;
use App\Http\Controllers\ClientController\UserController;
use App\Http\Controllers\ClientController\ResourceController;
use App\Http\Controllers\ClientController\NetworkController;
use App\Http\Controllers\ClientController\SettingController;
use App\Http\Controllers\ClientController\CouponController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ClientController\MessageController;
use App\Http\Controllers\ClientController\StoryController;
use App\Http\Controllers\ClientController\TwilioController;
use App\Http\Controllers\GoogleAuth\GoogleController;
use App\Http\Controllers\AdminControllers\VersionController;
use App\Http\Controllers\AdminControllers\InformationController;

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



//    client dashboard
    
    //90 days plan test route
   
    //end here
    

    // Route::get('/stripe-checkout', [PaymentController::class, 'showPaymentForm'])->name('stripe_checkout');
    // Route::post('/stripe', [PaymentController::class, 'processPayment'])->name('process_payment');




    
   
   

    
   

    

    

   
  

    Route::post('/user-feedback', [UserController::class, 'userFeedback'])->name('user-feedback');
    
