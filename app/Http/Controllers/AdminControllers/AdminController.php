<?php

namespace App\Http\Controllers\AdminControllers;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\AssessmentIntro\AssessmentIntro;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Http\Requests\Admin\StripeSetting\UpdateStripeRequest;
use App\Models\Client\Feedback\Feedback;
use App\Models\HAIChai\Chatbot;
use App\Models\Upload\Upload;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentDetail;
use App\Models\AssessmentColorCode;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Client\Dashboard\ActionPlan;

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

            if (Helpers::getWebUser()['is_admin'] == Admin::IS_PRACTITIONER) {

                return view('practitioner-dashboard.user.grid', compact('grid', 'grid_code_color'));

            } else {

                return view('admin-dashboards.user.user_grid', compact('grid', 'grid_code_color'));

            }

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }

    }

    public function haiChat()
    {
        try {

            return view('admin-dashboards.new-hai-chat.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function embeddings($id)
    {
        try {

            return view('admin-dashboards.new-hai-chat.embedding', compact('id'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function embeddingGroups()
    {
        try {

            return view('admin-dashboards.new-hai-chat.group');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function haiChatDetail($name)
    {
        try {

            $chatName = Chatbot::getChatFromVendorName($name);

            return view('admin-dashboards.new-hai-chat.detail', compact('chatName'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function embeddingDetail($name)
    {
        try {

            return view('admin-dashboards.new-hai-chat.embedding-detail');

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

    public function userFeedback()
    {

        try {

            $feedbacks = Feedback::userFeedbacks(10);

            return view('admin-dashboards.user-feedback.index', compact('feedbacks'));

        } catch (\Exception $exception) {

            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }

    }

    public function deletedClients()
    {

        try {

            return view('admin-dashboards.user.deleted_user');

        } catch (\Exception $exception) {

            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }

    }

    public function assessments()
    {

        try {

            return view('admin-dashboards.assessments.assessments');

        } catch (\Exception $exception) {

            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }

    }

    public function profileOverview($id = null)
    {
        try {

            if (empty($id)) {

                $userId = Helpers::getWebUser()['id'];

                $assessment = Assessment::singleAssessmentFromId($id);

                $created_at = Carbon::parse($assessment['updated_at'])->format('F j, Y');

            } else {

                $assessment = Assessment::singleAssessmentFromId($id);

                $created_at = Carbon::parse($assessment['updated_at'])->format('F j, Y');

            }


            $get_user = User::getSingleUser($assessment['user_id']);

            $age = Carbon::parse($get_user['date_of_birth'])->age;

            $allStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];
            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
            $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
            $boundary = $assessment != null ? Assessment::getAlchemyDetail($assessment) : [];
            $communication = $assessment != null ? Assessment::getEnergy($assessment) : [];
            $perception_life = AssessmentIntro::getPerceptionStaticText();
            $perception = $assessment != null ? Assessment::getPreceptionReportDetail($assessment) : [];
            $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication, $assessment) : [];
            $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : [];

            ActionPlan::storeUserActionPlan($assessment, $get_user);

            $actionPlan = ActionPlan::getUserActionPlan($assessment['users'] ? $assessment['users']['id'] : '');

            $summary_static = AssessmentIntro::summaryIntro();
            $main_result = AssessmentIntro::mainResult();
            $cycle_life = AssessmentIntro::cycleLife();
            $trait_intro = AssessmentIntro::traitIntro();
            $motivation_intro = AssessmentIntro::motivationIntroduction();
            $intro_boundaries = AssessmentIntro::introBoundaries();
            $intro_communication = AssessmentIntro::introCommunication();
            $intro_energypool = AssessmentIntro::introEnergypool();


            if (Helpers::getWebUser()['is_admin'] == Admin::IS_PRACTITIONER) {
                return view('practitioner-dashboard.user.profile_overview', compact('allStyles', 'topTwoFeatures', 'assessment', 'actionPlan', 'boundary', 'perception', 'topCommunication', 'energyPool', 'perception_life', 'age', 'id', 'created_at'));
            } else {
                return view('admin-dashboards.user.client_profile_overview', compact('summary_static', 'main_result', 'cycle_life', 'trait_intro', 'motivation_intro', 'intro_boundaries', 'intro_communication', 'intro_energypool', 'allStyles', 'topTwoFeatures', 'assessment', 'actionPlan', 'boundary', 'perception', 'topCommunication', 'energyPool', 'perception_life', 'age', 'id', 'created_at'));
            }

        } catch (\Exception $exception) {
            $url = request()->fullUrl(); // Get the URL of the request
            $file = $exception->getFile(); // Get the file where the exception occurred
            $line = $exception->getLine(); // Get the line number
            $message = $exception->getMessage(); // Get the exception message

            $errorDetails = "Error at URL: $url\nFile: $file\nLine: $line\nMessage: $message";

            return Helpers::serverErrorResponse($errorDetails);
        }

    }

    public function downloadUserReport($id)
    {
        $assessment = Assessment::singleAssessmentFromId($id);
        $user_name = $assessment['users'] ? $assessment['users']['first_name'] . ' ' . $assessment['users']['last_name'] : '';
        $allStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];
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


        $summary_static = AssessmentIntro::summaryIntro();
        $main_result = AssessmentIntro::mainResult();
        $cycle_life = AssessmentIntro::cycleLife();
        $trait_intro = AssessmentIntro::traitIntro();
        $motivation_intro = AssessmentIntro::motivationIntroduction();
        $intro_boundaries = AssessmentIntro::introBoundaries();
        $intro_communication = AssessmentIntro::introCommunication();
        $intro_energypool = AssessmentIntro::introEnergypool();
        $intro_perceptionlife = AssessmentIntro::perceptionLife();

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->getDomPDF()->setHttpContext($contxt);

        $pdf->loadView('pdf.report_pdf', compact('summary_static', 'main_result', 'cycle_life',
            'trait_intro', 'motivation_intro', 'intro_boundaries', 'intro_perceptionlife', 'intro_communication', 'intro_energypool', 'allStyles', 'topTwoFeatures', 'assessment', 'boundary', 'perception', 'topCommunication', 'energyPool', 'user_name', 'style_position', 'feature_position', 'alchl_code', 'ep', 'pv'))->setOptions(['defaultFont' => 'Poppins, sans-serif']);
        $filename = $user_name . '_report.pdf';

        return $pdf->stream($filename);
    }

    public function userProfileImage(Request $request)
    {
        try {

            if ($request['image']) {

                $upload_id = Upload::uploadFile($request['image'], 200, 200, 'base64Image', 'png', true);

                $user = Helpers::getWebUser();

                $updateUser = User::profileUpload($user['id'], $upload_id);

                return response()->json([

                    'url' => $updateUser['photo_url']

                ]);

            } else {

                return response()->json([

                    'error' => 'No image provided.'

                ], 400);

            }

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function fineTune()
    {

        try {

            return view('admin-dashboards.new-hai-chat.fine-tune');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function haiChatPersona($name = null)
    {

        try {

            $brain = Chatbot::getChatFromVendorName($name);

            return view('admin-dashboards.new-hai-chat.detail', compact('brain'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function haiChatComparison()
    {

        try {

            return view('admin-dashboards.new-hai-chat.comparison');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function createBrain(Request $request)
    {

        try {

            $name = $request->session()->get('name', null);
            $description = $request->session()->get('description', null);

            return view('admin-dashboards.new-hai-chat.brains.create-brain', compact('name', 'description'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function editBrain($id)
    {

        try {

            return view('admin-dashboards.hai-chat.new-brains.edit-brain', compact('id'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function createCluster()
    {

        try {

            return view('admin-dashboards.new-hai-chat.clusters.create-cluster');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function editCluster($id)
    {

        try {

            return view('admin-dashboards.new-hai-chat.clusters.edit-cluster', compact('id'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function downloadZipFile()
    {

        $zipPath = storage_path('knowledge.zip');

        if (file_exists($zipPath)) {

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        abort(404);

    }

    public function haiDojo()
    {

        try {

            return view('admin-dashboards.new-hai-chat.hai-dojo.hai-dojo');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function downloadConversation()
    {

        $conversationPath = storage_path('app/training-session-files/conversation.jsonl');

        if (file_exists($conversationPath)) {

            return response()->download($conversationPath)->deleteFileAfterSend(true);
        }

        abort(404);

    }
}
