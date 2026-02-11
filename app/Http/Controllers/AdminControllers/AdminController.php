<?php

namespace App\Http\Controllers\AdminControllers;

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

use App\Models\HotSpotUser;
use App\Models\HotSpot;
use Illuminate\Support\Facades\Log;
use function App\Http\Controllers\Api\ClientController;

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

    public function welcomeDashboard()
    {
        try {

            return view('admin-dashboards.welcome-dashboard');

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

            $gridCodeColor = AssessmentColorCode::getCodeColor($grid['id']);

            return view('admin-dashboards.user.user_grid', compact('grid', 'gridCodeColor'));


        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }

    }

    public function activityLogs($id)
    {
        try {

            return view('admin-dashboards.user.activity_logs', compact('id'));


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
            $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication, $assessment) : [];
            $perception_life = AssessmentIntro::getPerceptionStaticText();
            $perception = $assessment != null ? Assessment::getPerceptionReportDetail($assessment) : [];
            $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : [];

            ActionPlan::storeUserActionPlan($assessment, $get_user['plan_name']);

            $actionPlan = ActionPlan::getUserActionPlan($assessment['users'] ? $assessment['users']['id'] : '');

            $summary_static = AssessmentIntro::summaryIntro();
            $main_result = AssessmentIntro::mainResult();
            $cycle_life = AssessmentIntro::cycleLife();
            $trait_intro = AssessmentIntro::traitIntro();
            $motivation_intro = AssessmentIntro::motivationIntroduction();
            $intro_boundaries = AssessmentIntro::introBoundaries();
            $intro_communication = AssessmentIntro::introCommunication();
            $intro_energypool = AssessmentIntro::introEnergypool();

            return view('admin-dashboards.user.client_profile_overview', compact('summary_static', 'main_result', 'cycle_life', 'trait_intro', 'motivation_intro', 'intro_boundaries', 'intro_communication', 'intro_energypool', 'allStyles', 'topTwoFeatures', 'assessment', 'actionPlan', 'boundary', 'perception', 'topCommunication', 'energyPool', 'perception_life', 'age', 'id', 'created_at'));

        } catch (\Exception $exception) {
            $url = request()->fullUrl(); // Get the URL of the request
            $file = $exception->getFile(); // Get the file where the exception occurred
            $line = $exception->getLine(); // Get the line number
            $message = $exception->getMessage(); // Get the exception message

            $errorDetails = "Error at URL: $url\nFile: $file\nLine: $line\nMessage: $message";

            return Helpers::serverErrorResponse($errorDetails);
        }

    }

    private function calculateShifts($category, $prevData, $currData, $descField)
    {
        $normalize = fn($item) =>
        is_array($item) && isset($item[0]) ? $item : ($item ? [$item] : []);

        $prevArr = collect($normalize($prevData))
            ->pluck('name')
            ->filter()
            ->values()
            ->toArray();

        $currArr = collect($normalize($currData))
            ->pluck('name')
            ->filter()
            ->values()
            ->toArray();

        $removed = array_values(array_diff($prevArr, $currArr));
        $added   = array_values(array_diff($currArr, $prevArr));

        $max = max(count($removed), count($added));
        $shifts = [];

        for ($i = 0; $i < $max; $i++) {
            $shifts[] = [
                'category'    => $category,
                'prev_value'  => $removed[$i] ?? ($prevArr[0] ?? null),
                'curr_value'  => $added[$i] ?? ($currArr[0] ?? null),
                'description' => $descField,
            ];
        }

        return $shifts;
    }



    public function hotspotDetail($user_id)
    {
        try {

            if (empty($user_id)) {
                abort(404, "User not found");
            }

            // -----------------------
            // Get All assessments
            // -----------------------
            $assessmentIds = HotSpotUser::where('user_id', $user_id)
                ->distinct()
                ->pluck('assessment_id');

            $assessmentsRaw = HotSpotUser::where('user_id', $user_id)
                ->whereIn('assessment_id', $assessmentIds)
                ->get()
                ->groupBy('assessment_id');

            // -----------------------
            // Preload all hotspot names once to avoid N+1
            // -----------------------
           $hotspotIds = $assessmentsRaw->flatten()
            ->pluck('hotspot_id')
            ->filter()
            ->unique()
            ->toArray();

            $hotspotNames = HotSpot::whereIn('id', $hotspotIds)->pluck('name', 'id');

            // -----------------------
            // Map assessments to structured array
            // -----------------------
           $assessments = $assessmentsRaw->map(function ($rows, $assessmentId) use ($hotspotNames) {
                return [
                    'assessment_id' => $assessmentId,
                    'date' => optional($rows->first())->created_at?->format('F j, Y'),
                    'hotspots' => $rows->map(function ($row) use ($hotspotNames) {
                        return [
                            'priority' => $row->hotspot_score,
                            'name' => $hotspotNames[$row->hotspot_id] ?? null, // get name from Hotspot table
                            'names' => $row->names,
                            'shift_interval' => $row->shift_interval,
                            'id' => $row->hotspot_id,
                        ];
                    })->values()
                ];
            })->values();

            $assessments = $assessments
                ->sortBy(fn ($a) => \Carbon\Carbon::parse($a['date']))
                ->values();
            // -----------------------
            // Prepare trend comparisons
            // -----------------------

            $trendComparisons = [];
            $hotspotMessages = config('hotspot_messages'); // make sure you have this config

            $totalAssessments = $assessments->count();
            $dob = User::find($user_id)->date_of_birth;

            for ($i = 0; $i < $totalAssessments - 1; $i++) {
                $prev = $assessments[$i];
                $curr = $assessments[$i + 1];

                $previousAssessmentId = $prev['assessment_id'];
                $latestAssessmentId = $curr['assessment_id'];

                // -----------------------
                // Trend Direction
                // -----------------------
                $currPriorities = $curr['hotspots']->pluck('priority', 'name');
                $prevPriorities = $prev['hotspots']->pluck('priority', 'name');

                $minCurr = $currPriorities->isNotEmpty() ? min($currPriorities->all()) : null;
                $minPrev = $prevPriorities->isNotEmpty() ? min($prevPriorities->all()) : null;

                if (!is_null($minCurr) && !is_null($minPrev)) {
                    if ($minCurr > $minPrev) {
                        $trend = 'Positive Trend';
                        $message = "User moved from a severe issue (#{$minPrev}) to a lesser issue (#{$minCurr}).";
                    } elseif ($minCurr < $minPrev) {
                        $trend = 'Negative Trend';
                        $message = "User developed a more severe issue (#{$minCurr}) from previous (#{$minPrev}).";
                    } else {
                        $trend = 'No Change';
                        $message = "The most severe issue (#{$minCurr}) has not changed.";
                    }
                } else {
                    $trend = 'No Data';
                    $message = 'Insufficient data for trend analysis.';
                }

                // -----------------------
                // Interval Shift
                // -----------------------
                $prevIntervals = $prev['hotspots']->pluck('shift_interval', 'id');
                $currIntervals = $curr['hotspots']->pluck('shift_interval', 'id');

                $intervalShift = $currIntervals->contains(function ($interval, $hotspotId) use ($prevIntervals) {
                    return isset($prevIntervals[$hotspotId]) && $prevIntervals[$hotspotId] !== $interval;
                });

                // -----------------------
                // Hotspot Delta
                // -----------------------
                // collect previous and current scores only

                $prevScores = $prev['hotspots']->pluck('priority')->toArray();
                $currScores = $curr['hotspots']->pluck('priority')->toArray();


                $resolved = $prev['hotspots']->filter(function($hPrev) use ($curr) {
                    return !$curr['hotspots']->contains(function($hCurr) use ($hPrev) {
                        return $hCurr['priority'] === $hPrev['priority'];
                    });
                })->map(fn($h) => [
                    'priority' => $h['priority'],
                    'name' => $h['name'],
                ])->values()->all();

                $new = $curr['hotspots']->filter(function($hCurr) use ($prev) {
                    return !$prev['hotspots']->contains(function($hPrev) use ($hCurr) {
                        return $hPrev['priority'] === $hCurr['priority'];
                    });
                })->map(fn($h) => [
                    'priority' => $h['priority'],
                    'name' => $h['name'],
                ])->values()->all();

                $persistent = $curr['hotspots']->filter(function($hCurr) use ($prev) {
                    return $prev['hotspots']->contains(function($hPrev) use ($hCurr) {
                        return $hPrev['priority'] === $hCurr['priority'];
                    });
                })->map(fn($h) => [
                    'priority' => $h['priority'],
                    'name' => $h['name'],
                ])->values()->all();

                // Authentic Shifts

                $normalize = fn($item) => is_array($item) && isset($item[0]) ? $item : ($item ? [$item] : []);
                $latestAssessment = Assessment::singleAssessmentFromId($latestAssessmentId, null);
                $latestCore = Assessment::getCoreState($latestAssessment, $dob);

                $previousAssessment = Assessment::singleAssessmentFromId($previousAssessmentId, null);
                $prevCore = Assessment::getCoreState($previousAssessment, $dob);




                $traitsShifts =$this->calculateShifts(
                    $hotspotMessages['traits']['label'],
                    $prevCore['topThreeStyles'] ?? [],
                    $latestCore['topThreeStyles'] ?? [],
                    $hotspotMessages['traits']['message']
                );

                $driversShifts = $this->calculateShifts(
                    $hotspotMessages['drivers']['label'],
                    $prevCore['topTwoFeatures'] ?? [],
                    $latestCore['topTwoFeatures'] ?? [],
                    $hotspotMessages['drivers']['message']
                );

                $alchemyShifts = $this->calculateShifts(
                    $hotspotMessages['alchemy']['label'],
                    $prevCore['boundary'] ?? [],
                    $latestCore['boundary'] ?? [],
                    $hotspotMessages['alchemy']['message']
                );

                $commShifts = $this->calculateShifts(
                    $hotspotMessages['comm_style']['label'],
                    $prevCore['topCommunication'] ?? [],
                    $latestCore['topCommunication'] ?? [],
                    $hotspotMessages['comm_style']['message']
                );

                $perceptionShifts = $this->calculateShifts(
                    $hotspotMessages['perception']['label'],
                    $prevCore['perception'] ?? [],
                    $latestCore['perception'] ?? [],
                    $hotspotMessages['perception']['message']
                );

                $energyShifts = $this->calculateShifts(
                    $hotspotMessages['energy_pool']['label'],
                    $prevCore['energyPool'] ?? [],
                    $latestCore['energyPool'] ?? [],
                    $hotspotMessages['energy_pool']['message']
                );

                // Flatten all categories except interval_of_life
                $authenticShifts = [
                    'interval_of_life' => $intervalShift,
                    'traits'           => $traitsShifts,
                    'drivers'          => $driversShifts,
                    'alchemy'          => $alchemyShifts,
                    'comm_style'       => $commShifts,
                    'perception'       => $perceptionShifts,
                    'energy_pool'      => $energyShifts
                ];

                // -----------------------
                // HAI Analysis Prompt Context
                // -----------------------
                $prevMinScore = min($prevPriorities->all());
                $currScores = $currPriorities->values()->all();

                if (!in_array($prevMinScore, $currScores)) {
                    $hotspotNames = collect($prev['hotspots'])
                        ->filter(fn($h) => $h['priority'] === $prevMinScore)
                        ->pluck('names') // directly pluck the name column
                        ->toArray();

                    $hotspotNameStr = implode(', ', $hotspotNames);

                    $primaryWin = "Eliminated Priority #{$prevMinScore} Drain ({$hotspotNameStr})";
                } else {
                    $primaryWin = null;
                }

                // find the hotspot(s) with the min priority in current assessment
                $maxCurrScore = min($currPriorities->all());
                $currHotspotNames = collect($curr['hotspots'])
                    ->filter(fn($h) => $h['priority'] === $maxCurrScore)
                    ->pluck('names')
                    ->toArray();

                $currHotspotNameStr = implode(', ', $currHotspotNames);

                $currentHighestDrain = $currHotspotNameStr
                    ? "Hotspot #{$maxCurrScore} ({$currHotspotNameStr})"
                    : null;

                $contextNote = $hotspotMessages['trend_context'][$trend] ?? $hotspotMessages['trend_context']['NEUTRAL'];

                // Flatten all authentic shifts
                $authentic_element_shifts = [];
                foreach ($authenticShifts as $category => $shifts) {
                    if ($category === 'interval_of_life') continue;
                    foreach ($shifts as $s) {
                        $authentic_element_shifts[] = [
                            'category'   => ucwords(str_replace('_', ' ', $category)),
                            'prev_value' => $s['prev_value'] ?? 'None',
                            'curr_value' => $s['curr_value'] ?? 'None',
                            'description'=> $s['description'] ?? '',
                        ];
                    }
                }

                $trendComparisons[] = [
                    'current_assessment_number' => $i + 2,
                    'previous_assessment_number' => $i + 1,
                    'trend' => $trend,
                    'message' => $message,
                    'date_current' => $curr['date'],
                    'date_previous' => $prev['date'],
                    'interval_shift' => $intervalShift,
                    'hotspot_delta' => [
                        'resolved' => $resolved,
                        'new' => $new,
                        'persistent' => $persistent,
                    ],
                    'authentic_element_shifts' =>  $authentic_element_shifts,
                    'hai_analysis_prompt_context' => [
                        'primary_win' => $primaryWin,
                        'current_highest_drain' => $currentHighestDrain,
                        'context_note' => $contextNote,
                    ],
                ];
            }

            return view('admin-dashboards.user.hotspot_detail', compact('assessments', 'trendComparisons'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            abort(500, 'Something went wrong!');
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
        $perception = $assessment != null ? Assessment::getPerceptionReportDetail($assessment) : [];
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



}
