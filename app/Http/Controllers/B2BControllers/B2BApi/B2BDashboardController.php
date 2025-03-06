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

            $candidate = B2BBusinessCandidates::getBusinessCandidate();
            
            $optimizationPlan = ActionPlan::getUserActionPlan($candidate['candidate_id'] ?? '');

            $coreState = Assessment::getCoreState($candidate['assessments'] ?? '');

            $data = [
               
                'candidates-name' => isset($candidate['users']) ? ($candidate['users']['first_name'] . ' ' . $candidate['users']['last_name']) : '',
                'optimization-plan' => $optimizationPlan ,
                'core-state' => $coreState ,
            ];

            return Helpers::successResponse('candidates optimization and core state', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }
}
