<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\Helpers;
use App\Helpers\Points\PointHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\AssessmentDetail;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Client\Feedback\Feedback;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\GenerateFile\PdfGenerate;


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
            $alchl_code = Assessment::getAlchlCode($id);

            $style_position = AssessmentColorCode::getStylePosition($id);
            $feature_position = AssessmentColorCode::getFeaturePosition($id);

            $user = Auth::user();

            return view('client-dashboard.user.client_report', compact('reports', 'user', 'id', 'style_position', 'feature_position','alchl_code'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userFeedback(Request $request){

        try {

            $feedback = new Feedback();

            $dataArray = $request->only($feedback->getFillable());

            $dataArray['user_id'] = Helpers::getWebUser()->id;

            Feedback::storeClientFeedback($dataArray);

            PointHelper::addPointsOnFeedbackSubmission();

            return Helpers::successResponse('Thank you for your feedback! We have given you a point as a token of our appreciation!');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function profileOverview($slug1, $slug2, $id = null)
    {
        try {

            $id = last(request()->segments());

            $user = Helpers::getWebUser()['is_admin'];
            $user_age = Helpers::getWebUser()->date_of_birth;
            $age = Carbon::parse($user_age)->age;
            $assessment = Assessment::singleAssessmentFromId($id);
            $allStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];
            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
            $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
            $boundary = $assessment != null ? Assessment::getAlchemyDetail($assessment) : [];
            $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
            $perception_life = CodeDetail::getPerceptionStaticText();
            $perception = $assessment != null ? Assessment::getPreceptionReportDetail($assessment) : [];
            $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication) : [];
            $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : [];
            $actionPlan = ActionPlan::userActionPlan();

            return view('client-dashboard.user.client_profile_overview', compact('allStyles','topTwoFeatures','assessment', 'actionPlan','boundary','perception','topCommunication','energyPool','perception_life', 'age'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function downloadUserReport($id)
    {
        $user_name = Helpers::getWebUser()->first_name. ' ' . Helpers::getWebUser()->last_name;
        $assessment = Assessment::singleAssessmentFromId($id);
        $Styles = $assessment != null ? Assessment::getAllStyles($assessment) : [];
        $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
        $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
        $boundary = $assessment != null ? Assessment::getAlchemyDetail($assessment) : [];
        $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
        $perception = $assessment != null ? Assessment::getPreceptionReportDetail($assessment) : [];
        $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication) : [];
        $energyPool = $assessment != null ? Assessment::getEnergyPoolDetail($assessment) : [];
        $alchl_code = Assessment::getAlchlCode($id);
        $style_position = AssessmentColorCode::getStylePosition($id);
        $feature_position = AssessmentColorCode::getFeaturePosition($id);
        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];
        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];
        $ep = $positive + $negative;
        $pv = $positive - $negative;
        $allStyles = PdfGenerate::createGenerateFile($assessment['id'], Helpers::getWebUser()->id, $Styles);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->getDomPDF()->setHttpContext($contxt);

        $pdf->loadView('pdf.report_pdf', compact('allStyles','topTwoFeatures','assessment', 'boundary','perception','topCommunication','energyPool','user_name','style_position','feature_position','alchl_code','ep','pv'))->setOptions(['defaultFont' => 'Poppins, sans-serif']);
        $filename = $user_name. '_report.pdf';

        return $pdf->stream($filename);
    }
}
