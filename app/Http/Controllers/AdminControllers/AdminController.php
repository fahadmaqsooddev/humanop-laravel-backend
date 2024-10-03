<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Admin\Coupon\Coupon;
use App\Http\Requests\Admin\StripeSetting\UpdateStripeRequest;
use App\Models\Client\Feedback\Feedback;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentDetail;
use App\Models\AssessmentColorCode;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\GenerateFile\PdfGenerate;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $stripe = null;

    public function __construct(StripeSetting $stripe)
    {
        $this->genre = $stripe;
    }

    public function index()
    {
        try {

            return view('admin-dashboards.default');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function cms()
    {
        try {

            return view('admin-dashboards.cms');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function project()
    {
        try {

            return view('admin-dashboards.admin_projects');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function setting()
    {
        try {

            $account = StripeSetting::getSingle();
            $currentUser = Auth::user();

            return view('admin-dashboards.setting', compact('account', 'currentUser'));

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function stripeSetting(UpdateStripeRequest $request, $id)
    {
        try {
            $dataArray = $request->only($this->genre->getFillable());

            StripeSetting::updateStripeAccount($dataArray, $id);

            return redirect()->route('admin_setting')->with('success', 'Stripe Account Update Successfully');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function pagesUsersNewUser()
    {
        try {

            return view('admin-dashboards.new-user');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function pagesUsersReports()
    {
        try {

            return view('admin-dashboards.reports');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userAnswer($id)
    {
        try {

            $assessment_details = AssessmentDetail::getDetail($id);

            return view('admin-dashboards.user.answer', compact('assessment_details'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function grid($id)
    {
        try {

            $grid = Assessment::getGrid($id);

            $grid_code_color = AssessmentColorCode::getCodeColor($grid['id']);

            return view('admin-dashboards.user.user_grid', compact('grid', 'grid_code_color'));

        } catch (\Exception $exception) {

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

            return view('admin-dashboards.user.user_report', compact('reports', 'id','alchl_code','style_position','feature_position'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function haiChat()
    {
        try {

            return view('admin-dashboards.hai-chat');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userInfo($id)
    {
        try {
            $user = User::getSingleUser($id);
            return view('admin-dashboards.user.user_info', compact('user'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userDetail($id)
    {
        try {

            return view('admin-dashboards.user.user_detail', compact('id'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function allUsers()
    {
        try {

            return view('admin-dashboards.user.all_users');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
    public function allAdmins()
    {

        try {
            $admins = User::allSubAdmin();
            return view('admin-dashboards.all_admins', compact('admins'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function allQuestions()
    {
        try {

            return view('admin-dashboards.all_questions');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function abandonedAssessment()
    {
        try {

            $assessments = Assessment::abandonedAssessment();

            return view('admin-dashboards.user.abandoned_assessment', compact('assessments'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userFeedback(){

        try {

            $feedbacks = Feedback::userFeedbacks();

            return view('admin-dashboards.user-feedback.index', compact('feedbacks'));

        }catch (\Exception $exception){

            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }

    }

    public function deletedClients(){

        try {

            return view('admin-dashboards.user.deleted_user');

        }catch (\Exception $exception){

            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }

    }

    public function assessments(){

        try {

            return view('admin-dashboards.assessments.assessments');

        }catch (\Exception $exception){

            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }

    }

    public function profileOverview($id = null)
    {
        try {

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
            $energyPool = $assessment != null ? Assessment::getEnergyPoolDetail($assessment) : [];
            $actionPlan = ActionPlan::userActionPlan();

            return view('admin-dashboards.user.client_profile_overview', compact('allStyles','topTwoFeatures','assessment', 'actionPlan','boundary','perception','topCommunication','energyPool','perception_life', 'age', 'id'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function downloadUserReport($id)
    {
        $assessment = Assessment::singleAssessmentFromId($id);
        $user_name = $assessment['users'] ? $assessment['users']['first_name'] . ' ' . $assessment['users']['last_name'] : '';
        $Styles = $assessment != null ? Assessment::getAllStyles($assessment) : [];
        $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
        $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
        $boundary = $assessment != null ? Assessment::getAlchemyDetail($assessment) : [];
        $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
        $perception = $assessment != null ? Assessment::getPreceptionReportDetail($assessment) : [];
        $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication) : [];
        $energyPool = $assessment != null ? Assessment::getEnergyPoolDetail($assessment) : [];

        $allStyles = PdfGenerate::createGenerateFile($assessment['id'], $assessment['users']['id'], $Styles);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->getDomPDF()->setHttpContext($contxt);

        $pdf->loadView('pdf.report_pdf', compact('allStyles','topTwoFeatures','assessment', 'boundary','perception','topCommunication','energyPool','user_name'))->setOptions(['defaultFont' => 'Poppins, sans-serif']);
        $filename = $user_name. '_report.pdf';

        return $pdf->download($filename);
    }
}
