<?php

namespace App\Http\Controllers\v4\Api\ClientController\ImpactProject;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImpactProject;
use App\Models\ImpactContribution;
use App\Http\Requests\v4\Api\Client\ImpactContributionRequest;
use App\Helpers\Helpers;
use App\Models\UserRewardLog;

class ImpactProjectController extends Controller
{
       /**
     * GET: Fetch all impact initiatives for the user
     */

    public $user=null;

    public function __construct()
    {
        $this->user=Helpers::getUser();   
    }

    public function index(Request $request)
    {

        try {
    
         $projectsData = ImpactProject::fetchForUser($this->user);

         return Helpers::successResponse('Impact projects fetched successfully', $projectsData);

        } catch (\Exception $e) {
           
          return Helpers::serverErrorResponse($e->getMessage());
        }

    }

    /**
     * POST: Contribute HP to a project
     */
    public function contribute(ImpactContributionRequest $request)
    {
        try {
           

             $project = ImpactProject::where('status',1)
            ->where('id',$request->project_id)
            ->firstOrFail();
            $result = $project->contributeByUser($this->user);

           
            if ($result['success']) {
                return Helpers::successResponse('Contribution successful', [
                    'remaining_hp' => $result['remaining_hp']
                ]);
            } else {
                return Helpers::validationResponse($result['message']);
            }

        } catch (\Exception $e) {

            return Helpers::serverErrorResponse($e->getMessage());
        }
    }


    public function impactLogs()
    {
        try {
            $logs=ImpactProject::getLogs($this->user->id);
             return Helpers::successResponse('Logs Fetched successfully', $logs);
        } catch (\Exception $e) {
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    public function rewardLogs()
    {
        try {
          
            $logs = UserRewardLog::getLast24HoursLogs($this->user->id);
            return Helpers::successResponse('User Reward Logs Fetched successfully', $logs);

        } catch (\Exception $e) {
            
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }
}
