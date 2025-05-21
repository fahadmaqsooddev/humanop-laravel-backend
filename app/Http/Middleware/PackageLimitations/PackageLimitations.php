<?php

namespace App\Http\Middleware\PackageLimitations;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\UserCandidateInvite;
use App\Models\Client\Plan\Plan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageLimitations
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type)
    {
        $response = self::limitExceedResponses($type);

        if (Auth::guard('api')->check()) {

            $user_id = Helpers::getUser()->id;

            if ($plan_id = Helpers::getUser()->getsubscription()->first()) {

                $plan_id = $plan_id->stripe_price;

            } else {

                return Helpers::upgradePackageResponse('Please subscribe your plan first');

            }

            $limitations = Plan::singlePlan($plan_id);


            if ($type === 'add_members') {

                $getExistingMembers = B2BBusinessCandidates::where('business_id', $user_id)
                    ->where('role', Admin::IS_TEAM_MEMBER)
                    ->where('future_consideration', Admin::NOT_IN_FUTURE)
                    ->where('is_permanently_deleted', 0)
                    ->with(['users' => function ($q) {
                        $q->where('step', 3);
                    }])
                    ->get();

                $existingMemberCounts = 0;

                foreach ($getExistingMembers as $member) {
                    if (!empty($member->users)) {
                        $existingMemberCounts += 1;
                    }
                }

                $getMemberInvites = UserCandidateInvite::where('company_id', $user_id)->where('role', Admin::IS_TEAM_MEMBER)->get();

                $allMembers = $existingMemberCounts + count($getMemberInvites);

                if (($allMembers < (int)$limitations['no_of_team_members'])) {

                    return $next($request);

                }else{

                    return Helpers::upgradePackageResponse($response);

                }

            }

        } else {

            return Helpers::unauthResponse('Unauthenticated');
        }
    }

    public function limitExceedResponses($type = null)
    {

        $response = '';

        if ($type === 'add_members') {

            $response = 'Upgrade: You have reached the maximum number of Member for your account tier.';

        }

        return $response;

    }
}
