<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\Faq\FaqModel;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    //

    public function FaqQuestions()
    {
        try {
            return view('admin-dashboards.faq.index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }


    public function getFaqQuestionsData(Request $request)
    {
        try {

            $faqQuestionsData=FaqModel::allFaqsQuestions($request);
            
            return Helpers::successResponse('All Faq Questions', $faqQuestionsData);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

}
