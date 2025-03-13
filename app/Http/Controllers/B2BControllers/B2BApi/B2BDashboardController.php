<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\B2BCandidateStat;
use App\Models\B2B\IntentionOption;
use App\Models\B2B\B2BNotes;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\Request;
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
                $checkCandidateAndMember = User::getSingleUser($request['candidate_id']);

                if ($checkCandidateAndMember) {
                    $getAssessment = Assessment::getLatestAssessment($checkCandidateAndMember['id']);

                    if (!empty($getAssessment)) {
                        $optimizationPlan = ActionPlan::getUserActionPlan($checkCandidateAndMember['id']);
                        $coreState = Assessment::getCoreState($getAssessment, $checkCandidateAndMember['date_of_birth']);
                        $userTrait = Assessment::UserTraits($checkCandidateAndMember['id']);

                        return Helpers::successResponse('candidates optimization and core state', [
                            'candidates_name' => $checkCandidateAndMember['first_name'] . ' ' . $checkCandidateAndMember['last_name'],
                            'optimization_plan' => $optimizationPlan,
                            'core_state' => $coreState,
                            'user_trait' => $userTrait
                        ]);
                    }
                }
            } else {
                $candidate = B2BBusinessCandidates::getBusinessCandidate();

                if (!$candidate) {
                    return Helpers::successResponse('candidates optimization and core state', [
                        'candidates_name' => null,
                        'optimization_plan' => null,
                        'core_state' => null,
                        'user_trait' => null
                    ]);
                }

                $userId = $request->input('candidate_id', Helpers::getUser()['id']);
                $checkCandidateResult = B2BCandidateStat::getResult($userId);

                $isCandidateAvailable = !empty($checkCandidateResult);
                $isRecentUpdate = $isCandidateAvailable && Carbon::parse($checkCandidateResult['updated_at'])->gte(Carbon::now()->subHours(24));

                if (!$isRecentUpdate) {
                    $candidate = B2BBusinessCandidates::getBusinessCandidate();
                }

                if (!$candidate) {
                    return Helpers::successResponse('candidates optimization and core state', [
                        'candidates_name' => null,
                        'optimization_plan' => null,
                        'core_state' => null,
                        'user_trait' => null
                    ]);
                }

                $optimizationPlan = ActionPlan::getUserActionPlan($candidate['candidate_id']);
                $coreState = Assessment::getCoreState($candidate['assessments'], $candidate['users']['date_of_birth']);
                $userTrait = Assessment::UserTraits($candidate['users']['id']);

                if ($isCandidateAvailable) {
                    if (!$isRecentUpdate) {
                        B2BCandidateStat::updateRecord($candidate['candidate_id'], $optimizationPlan['id']);
                    }
                } else {
                    B2BCandidateStat::createRecord($candidate['candidate_id'], $optimizationPlan['id']);
                }

                return Helpers::successResponse('candidates optimization and core state', [
                    'candidates_name' => isset($candidate['assessments']) ? ($candidate['users']['first_name'] . ' ' . $candidate['users']['last_name']) : '',
                    'optimization_plan' => $optimizationPlan,
                    'core_state' => $coreState,
                    'user_trait' => $userTrait
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
