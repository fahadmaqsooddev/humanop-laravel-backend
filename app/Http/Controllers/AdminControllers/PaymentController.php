<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Helpers\Helpers;
use App\Models\Payment;

class PaymentController extends Controller
{

    public function PaymentHistory()
    {
        try {

            $payment_history = Payment::getAllPaymentHistory();

            return view('admin-dashboards.payment.payment_history', compact('payment_history'));

        }catch (\Exception $exception)
        {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

}
