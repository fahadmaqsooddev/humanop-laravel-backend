<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AssessmentDetail;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\Client\Feedback\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function userDetail($id)
    {
        try {

            return view('client-dashboard.user.client_user_detail', compact('id'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userInfo()
    {
        try {

            $user = Auth::user();
            return view('client-dashboard.user.client_user_info', compact('user'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function grid($id)
    {
        try {

            $grid = Assessment::getGrid($id);

            $grid_code_color = AssessmentColorCode::getCodeColor($grid['id']);

            return view('client-dashboard.user.client_grid', compact('grid', 'grid_code_color'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function report($id)
    {
        try {

            $reports = Assessment::getReport($id);
            $user = Auth::user();
            return view('client-dashboard.user.client_report', compact('reports', 'user', 'id'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userFeedback(Request $request){

        try {

            $feedback = new Feedback();

            $dataArray = $request->only($feedback->getFillable());

            Feedback::storeClientFeedback($dataArray);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
