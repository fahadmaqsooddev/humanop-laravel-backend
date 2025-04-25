<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\B2B\checkCnadidateRequest;
use App\Models\Assessment;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\B2BCandidateStat;
use App\Models\B2B\IntentionOption;
use App\Models\B2B\B2BNotes;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\B2B\CreateNotes;
use App\Http\Requests\B2B\UpdateNotes;

class B2BDashboardController extends Controller
{
    protected $auth;

    protected $user;

    public function __construct(User $user)
    {
        $this->auth = Auth::guard('api');

        $this->user = $user;
    }

    public function candidateOptimizationAndCoreState(\Illuminate\Http\Request $request)
    {
        try {
            if (!empty($request['candidate_id'])) {

                $checkShareStatus = B2BBusinessCandidates::checkShare($request['candidate_id']);

                if (!empty($checkShareStatus) && !empty($checkShareStatus['users']) && $checkShareStatus['share_data'] == Admin::SHARED_DATA) {

                    $userId = $checkShareStatus['users']['id'];

                    $getAssessment = Assessment::getLatestAssessment($userId);

                    if (!empty($getAssessment)) {
                        $optimizationPlan = $getAssessment ? ActionPlan::getUserActionPlan($userId) : null;
                        $coreState = $getAssessment ? Assessment::getCoreState($getAssessment, $checkShareStatus['users']['date_of_birth']) : null;
                        $userTrait = Assessment::UserTraits($userId);
                        $userNote = B2BNotes::getNoteFromUserId($userId) ?? '';

                        return Helpers::successResponse('candidates optimization and core state', [
                            'candidates_name' => ($checkShareStatus['users']['first_name'] ?? '') . ' ' . ($checkShareStatus['users']['last_name'] ?? ''),
                            'optimization_plan' => $optimizationPlan,
                            'core_state' => $coreState,
                            'user_trait' => $userTrait,
                            'user_note' => $userNote
                        ]);
                    } else {
                        return Helpers::successResponse('candidates optimization and core state', [
                            'candidates_name' => ($checkShareStatus['users']['first_name'] ?? '') . ' ' . ($checkShareStatus['users']['last_name'] ?? ''),
                            'optimization_plan' => null,
                            'core_state' => null,
                            'user_trait' => null,
                            'user_note' => null
                        ]);
                    }
                } else {
                    return Helpers::successResponse('candidates optimization and core state', [
                        'candidates_name' => ($checkShareStatus['users']['first_name'] ?? '') . ' ' . ($checkShareStatus['users']['last_name'] ?? ''),
                        'optimization_plan' => null,
                        'core_state' => null,
                        'user_trait' => null,
                        'user_note' => null
                    ]);
                }
            }

            // If no candidate_id is provided, fetch a random business candidate
            $candidate = B2BBusinessCandidates::getBusinessCandidate();

            if (!$candidate) {
                return Helpers::successResponse('candidates optimization and core state', [
                    'candidates_name' => null,
                    'optimization_plan' => null,
                    'core_state' => null,
                    'user_trait' => null,
                    'user_note' => null
                ]);
            }

            $userId = $candidate['users']['id'] ?? null;
//            $checkCandidateResult = B2BCandidateStat::getResult($userId);
//            $isCandidateAvailable = !empty($checkCandidateResult);

//            $isRecentUpdate = $isCandidateAvailable && Carbon::parse($checkCandidateResult['updated_at'])->gte(Carbon::now()->subHours(24));
//
//            if (!$isRecentUpdate) {
//                $candidate = B2BBusinessCandidates::getBusinessCandidate();
//            }
//
//            if (!$candidate) {
//                return Helpers::successResponse('candidates optimization and core state', [
//                    'candidates_name' => null,
//                    'optimization_plan' => null,
//                    'core_state' => null,
//                    'user_trait' => null,
//                    'user_note' => null
//                ]);
//            }

            $optimizationPlan = ActionPlan::getUserActionPlan($candidate['candidate_id']);
            $coreState = Assessment::getCoreState($candidate['assessments'], $candidate['users']['date_of_birth']);
            $userTrait = Assessment::UserTraits($candidate['users']['id']);
            $userNote = B2BNotes::getNoteFromUserId($candidate['users']['id']) ?? '';

//            if ($isCandidateAvailable) {
//                if (!$isRecentUpdate) {
//                    B2BCandidateStat::updateRecord($candidate['candidate_id'], $optimizationPlan['id']);
//                }
//            } else {
//                B2BCandidateStat::createRecord($candidate['candidate_id'], $optimizationPlan['id']);
//            }

            return Helpers::successResponse('candidates optimization and core state', [
                'candidates_name' => ($candidate['users']['first_name'] ?? '') . ' ' . ($candidate['users']['last_name'] ?? ''),
                'optimization_plan' => $optimizationPlan,
                'core_state' => $coreState,
                'user_trait' => $userTrait,
                'user_note' => $userNote
            ]);
        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse(
                'Error: ' . $exception->getMessage() .
                ' | Line: ' . $exception->getLine() .
                ' | File: ' . $exception->getFile()
            );
        }

// catch (\Exception $exception) {
//            return Helpers::serverErrorResponse($exception->getMessage());
//        }
    }

    public function b2bOptimizationAndCoreState(\Illuminate\Http\Request $request)
    {
        try {

            $user = User::getSingleUser($request['id']);

            $getAssessment = Assessment::getLatestAssessment($user['id']);

            if (!empty($getAssessment)) {

                $optimizationPlan = $getAssessment ? ActionPlan::getUserActionPlan($user['id']) : null;

                $coreState = $getAssessment ? Assessment::getCoreState($getAssessment, $user['date_of_birth']) : null;

                $userTrait = Assessment::UserTraits($user['id']);

                $userNote = B2BNotes::getNoteFromUserId($user['id']) ?? '';

                return Helpers::successResponse('candidates optimization and core state', [
                    'candidates_name' => ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''),
                    'optimization_plan' => $optimizationPlan,
                    'core_state' => $coreState,
                    'user_trait' => $userTrait,
                    'user_note' => $userNote
                ]);

            } else {

                return Helpers::successResponse('candidates optimization and core state', [
                    'candidates_name' => ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''),
                    'optimization_plan' => null,
                    'core_state' => null,
                    'user_trait' => null,
                    'user_note' => null
                ]);
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function StoreNotes(CreateNotes $request)
    {
        try {

            $dataArray = $request->only(['user_id', 'note_id', 'note']);

            $getNote = B2BNotes::singleNote($dataArray['note_id'] ?? null);

            if (!empty($dataArray['note_id']) && !empty($getNote)) {

                B2BNotes::UpdateNote($dataArray);

            } else {

                B2BNotes::CreateNote($dataArray);

            }

            return Helpers::successResponse('Note Store Successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function getNote(\Illuminate\Http\Request $request)
    {
        try {

            $note = B2BNotes::getNoteFromUserId($request['user_id']);

            return Helpers::successResponse('get note', $note);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }
}
