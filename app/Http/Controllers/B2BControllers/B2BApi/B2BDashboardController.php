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

    public function candidateOptimizationAndCoreState()
    {
        try {

            $checkCandidateResult = B2BCandidateStat::getResult();

            $isCandidateAvailable = !empty($checkCandidateResult);

            $isRecentUpdate = $isCandidateAvailable && $checkCandidateResult['updated_at'] >= Carbon::now()->subHours(24);

            if ($isRecentUpdate) {

                $candidate = $checkCandidateResult;

            } else {

                $candidate = B2BBusinessCandidates::getBusinessCandidate();

            }

            $optimizationPlan = ActionPlan::getUserActionPlan($candidate['candidate_id'] ?? '');

            $coreState = Assessment::getCoreState($candidate['assessments'] ?? '', $candidate['users']['date_of_birth'] ?? '');

            $user_trait = Assessment::UserTraits($candidate['users']['id'] ?? '');

            if ($isCandidateAvailable && !$isRecentUpdate) {

                B2BCandidateStat::updateRecord($candidate['candidate_id'], $optimizationPlan['id']);

            } elseif (!$isCandidateAvailable && !empty($candidate)) {

                B2BCandidateStat::createRecord($candidate['candidate_id'], $optimizationPlan['id']);

            }

            $data = [
                'candidates_name' => isset($candidate['assessments']) ? ($candidate['users']['first_name'] . ' ' . $candidate['users']['last_name']) : '',
                'optimization_plan' => $optimizationPlan,
                'core_state' => $coreState,
                'user_trait' => $user_trait
            ];

            return Helpers::successResponse('candidates optimization and core state', $data);

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


    public function AllIntentions(){
        try {

            $data=IntentionOption::allIntentions();

            return Helpers::successResponse('All Intentions', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }
}
