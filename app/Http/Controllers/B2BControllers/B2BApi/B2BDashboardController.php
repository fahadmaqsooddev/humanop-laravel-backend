<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\B2BCandidateStat;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class B2BDashboardController extends Controller
{
    protected $auth;

    protected $user;

    public function __construct(User $user)
    {
        $this->auth = Auth::guard('api');

        $this->user = $user;
    }


    // public function candidateOptimizationAndCoreState()
    // {
    //     try {
            
            
    //         $result=B2BCandidateStat::getResult();
    //         if ($result && $result->created_at && $result->created_at->gt(Carbon::now()->subHours(24))) {

    //             $optimizationPlan = ActionPlan::getUserActionPlan($result['candidate_id'] ?? '');

    //             $coreState = Assessment::getCoreState($result['assessments'] ?? '', $result['users']['date_of_birth'] ?? '');

    //         }
    //         else{

    //             if($result){

    //                 $candidate = B2BBusinessCandidates::getBusinessCandidate();

    //                 $optimizationPlan = ActionPlan::getUserActionPlan($candidate['candidate_id'] ?? '');
        
    //                 $coreState = Assessment::getCoreState($candidate['assessments'] ?? '', $candidate['users']['date_of_birth'] ?? '');

    //                 B2BCandidateStat::updateRecord($candidate['candidate_id'],$optimizationPlan['id']);
                 
    //             }
    //             else
    //             {
    //                 $candidate = B2BBusinessCandidates::getBusinessCandidate();

    //                 $optimizationPlan = ActionPlan::getUserActionPlan($candidate['candidate_id'] ?? '');
        
    //                 $coreState = Assessment::getCoreState($candidate['assessments'] ?? '', $candidate['users']['date_of_birth'] ?? '');
                   
    //                 B2BCandidateStat::createRecord($candidate['candidate_id'],$optimizationPlan['id']);
    //             }
    //         }
            
    //         // $candidate = B2BBusinessCandidates::getBusinessCandidate();

    //         // $optimizationPlan = ActionPlan::getUserActionPlan($candidate['candidate_id'] ?? '');

    //         // $coreState = Assessment::getCoreState($candidate['assessments'] ?? '', $candidate['users']['date_of_birth'] ?? '');

    //         $data = [

    //             'candidates-name' => isset($candidate['users']) ? ($candidate['users']['first_name'] . ' ' . $candidate['users']['last_name']) : '',
    //             'optimization-plan' => $optimizationPlan ,
    //             'core-state' => $coreState ,
    //         ];

    //         return Helpers::successResponse('candidates optimization and core state', $data);

    //     } catch (\Exception $exception) {

    //         return Helpers::serverErrorResponse($exception->getMessage());

    //     }
    // }

    

    public function candidateOptimizationAndCoreState()
{
    try {

        $result = B2BCandidateStat::getResult();

        $isRecent = $result && $result->created_at && $result->created_at->gt(Carbon::now()->subHours(24));

        if ($isRecent) {

            $candidateId = $result['candidate_id'] ?? '';
            $assessments = $result['assessments'] ?? '';
            $dob = $result['users']['date_of_birth'] ?? '';

        } else {
            $candidate = B2BBusinessCandidates::getBusinessCandidate();

            $candidateId = $candidate['candidate_id'] ?? '';
            $assessments = $candidate['assessments'] ?? '';
            $dob = $candidate['users']['date_of_birth'] ?? '';

            if ($result) {
                B2BCandidateStat::updateRecord($candidateId, ActionPlan::getUserActionPlan($candidateId)['id']);
            } else {
                B2BCandidateStat::createRecord($candidateId, ActionPlan::getUserActionPlan($candidateId)['id']);
            }
        }

        $optimizationPlan = ActionPlan::getUserActionPlan($candidateId);
        $coreState = Assessment::getCoreState($assessments, $dob);

        $data = [
            'candidates-name' => isset($candidate['users']) 
                ? ($candidate['users']['first_name'] . ' ' . $candidate['users']['last_name']) 
                : '',
            'optimization-plan' => $optimizationPlan,
            'core-state' => $coreState,
        ];

        return Helpers::successResponse('candidates optimization and core state', $data);
        
    } catch (\Exception $exception) {
        return Helpers::serverErrorResponse([
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
    }
}

}
