<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

            $candidates = B2BBusinessCandidates::getBusinessCandidate();

            $optimizationPlan = ActionPlan::getUserActionPlan($candidates[0]['candidate_id']);

            $coreState = Assessment::getCoreState($candidates[0]['assessments'][0]);

            $data = [
                'candidates-name' => $candidates[0]['users']['first_name'] . ' ' . $candidates[0]['users']['last_name'],
                'optimization-plan' => $optimizationPlan,
                'core-state' => $coreState,
            ];

            return Helpers::successResponse('candidates optimization and core state', $data);

        } catch (\Exception $exception) {


        }
    }
}
