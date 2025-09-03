<?php

namespace App\Http\Controllers\Api\ClientController\HumanNetwork;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\HumanNetwork\ConnectUnConnectRequest;
use App\Http\Requests\Api\Client\HumanNetwork\CoreStatsComparisonRequest;
use App\Http\Requests\Api\Client\HumanNetwork\FollowUnFollowRequest;
use App\Http\Requests\Api\Client\HumanNetwork\SetScoreForMatchingConnectionRequest;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Assessment;
use App\Models\Client\Connection\Connection;
use App\Models\Client\Follow\Follow;
use App\Models\NetworkTutorial\NetworkTutorial;
use App\Models\User;
use Illuminate\Http\Request;

class HumanNetworkController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function followUnfollow(FollowUnFollowRequest $request)
    {

        try {

            Follow::followUnFollowForApi($request);

            return Helpers::successResponse('User ' . $request->type . 'ed successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function followers(Request $request)
    {

        try {

            $followers = Follow::paginatedFollowers($request);

            return Helpers::successResponse('User followers', $followers, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function following(Request $request)
    {

        try {

            $following = Follow::paginatedFollowing($request);

            return Helpers::successResponse('User followers', $following, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function connectUnconnect(ConnectUnConnectRequest $request)
    {

        try {

            $request['user_id'] = Helpers::getUser()->id;

            Connection::connectUnConnect($request->all());

            return Helpers::successResponse('User ' . $request->type . 'ed successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function users(Request $request)
    {

        try {

            $users = User::allPaginatedClients($request);

            return Helpers::successResponse('All users', $users, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function connectionRequests(Request $request)
    {

        try {

            $connection_requests = Connection::paginatedConnectionRequests($request);

            return Helpers::successResponse('Connection requests', $connection_requests, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function networkTutorials()
    {

        try {

            $tutorials = NetworkTutorial::allTutorials();

            return Helpers::successResponse('Network Tutorials', $tutorials);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function coreStatsComparisonBetweenUsers(CoreStatsComparisonRequest $request)
    {

        try {

            $plan = Helpers::getUser()['plan_name'];

            $coreState = [];

            $userIds = $request['user_id'];

            if ($plan == 'Freemium') {

                if (count($userIds) > 2) {

                    return Helpers::validationResponse('At least 2 users are required for the Freemium plan.');
                }
            } else {

                if (count($userIds) > 3) {

                    return Helpers::validationResponse('At least 2 users are required for the Freemium plan.');
                }
            }


            foreach ($userIds as $key => $userId) {

                $user_name = User::whereId($userId)->first();

                $assessment = Assessment::getLatestAssessment($userId);

                if ($assessment == null) {

                    return Helpers::validationResponse($user_name['first_name'] . ' ' . $user_name['last_name'] . ' has no assessment');
                }

                $coreState[$key] = Assessment::getCoreState($assessment, $user_name->date_of_birth);

            }

            return Helpers::successResponse('Core Stats Comparison Between' . count($userIds) . '', $coreState);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function connections(Request $request)
    {

        try {

            $connections = Connection::userPaginatedConnections($request);

            foreach ($connections as $connection) {

                $connection->setAppends(['thread_id']);
            }


            return Helpers::successResponse('Connections', $connections, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function styleFeatureCodes()
    {

        try {

            $style_feature_codes = CodeDetail::styleAndFeatureCode();

            $style_feature_codes = array_values($style_feature_codes->toArray());

            return Helpers::successResponse('Style and Feature codes', $style_feature_codes);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function alchemyCodes()
    {

        try {

            $alchemy_codes = CodeDetail::alchemyCodes();

            $alchemy_codes = array_values($alchemy_codes->toArray());

            return Helpers::successResponse('Style and Feature codes', $alchemy_codes);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function insightsOfConnection()
    {
        try {

            $userId = Helpers::getUser()['id'];

            $plaName = Helpers::getUser()['plan_name'];

            $getAssessment = Assessment::getLatestAssessment($userId);

            if ($getAssessment && $plaName === 'Core') {

                $currentUserTraits = Assessment::highLightStyle($getAssessment);

                $allUsers = User::whereHas('haiAssessments')->with('haiAssessments')->whereIn('is_admin', [Admin::IS_B2B, Admin::IS_CUSTOMER])->get();

                $matchedUsers = [];

                foreach ($allUsers as $user) {

                    $userTraits = Assessment::highLightStyle($user->haiAssessments);

                    $matchedTraits = array_intersect($currentUserTraits, $userTraits);

                    $matchCount = count($matchedTraits);

                    if ($matchCount > 2) {

                        $matchedUsers[] = [
                            'user' => $user,

                        ];

                    }

                }


                $topMatchedUsers = array_slice($matchedUsers, 0, 10);

                return Helpers::successResponse('insights of connection', $topMatchedUsers);

            } else {
                return Helpers::successResponse('Not found', '');
            }


        } catch (\Exception $e) {
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    public function matchingConnection(Request $request)
    {

        try {

            $loginUser = Helpers::getUser();

            if ($loginUser['plan_name'] == 'Core') {

                $users = User::query();

                if (!empty($request['search_name'])) {

                    $search_name = $request['search_name'];

                    $users = $users->where(function ($q) use ($search_name) {

                        $q->where('first_name', 'LIKE', "%$search_name%")
                            ->orWhere('last_name', 'LIKE', "%$search_name%")
                            ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");
                    });
                }

                $users = $users->whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])->whereNull('b2b_deleted_at')->get();

                $matchingUsers = [];

                foreach ($users as $user) {

                    $getFirstUserAssessment = Assessment::getLatestAssessment($loginUser['id']);

                    $getSecondUserAssessment = Assessment::getLatestAssessment($user['id']);

                    if (!empty($getFirstUserAssessment) && !empty($getSecondUserAssessment)) {

                        // ==================== Trait Compatability Calculator =========================== //

                        $getFirstUserTraitWeight = Assessment::getTopThreeTraitWeight($getFirstUserAssessment);

                        $getSecondUserTraitWeight = Assessment::getTopThreeTraitWeight($getSecondUserAssessment);

                        if ($getFirstUserTraitWeight != null && $getSecondUserTraitWeight != null) {

                            $compatabilityCalculator = Helpers::getCompatabilityBetweenTwoPerson($getFirstUserTraitWeight, $getSecondUserTraitWeight, $getFirstUserAssessment, $getSecondUserAssessment);

                            if ($compatabilityCalculator >= $loginUser['matching_connection_score']){

                                $matchingUsers[] = $user;
                            }

                        }

                    }
                }

                return Helpers::successResponse('Matching Connections', $matchingUsers);

            }else{

                return Helpers::validationResponse('Only for paid users');

            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function setScoreForMatchingConnection(SetScoreForMatchingConnectionRequest $request)
    {

        try {

            $user = Helpers::getUser();

            if ($user['plan_name'] === 'Core') {

                User::setConnectionScore($user['id'], $request['matching_connection_score']);

                return Helpers::successResponse('Matching Connection Score Updated');

            }else{

                return Helpers::validationResponse('Only for paid users');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function userTraits(Request $request)
    {

        try {

            $loginUser = Helpers::getUser();

            if (!empty($request['user_id'])){

                $assessment = Assessment::getLatestAssessment($request['user_id']);

                if (!empty($assessment)) {


                    if ($loginUser['plan_name'] == 'Core') {

                        $styleCodes = Assessment::authenticTraits($assessment);

                        $public_name = [];

                        foreach ($styleCodes as $style) {

                            $public_name[] = $style['public_name'];
                        }

                        return $public_name;

                    } else {

                        $styleCodes = Assessment::getAllStyles($assessment);

                        $public_name = [];

                        foreach ($styleCodes as $style) {

                            $public_name[] = $style['public_name'];
                        }

                        return $public_name;

                    }

                }else{

                    return Helpers::validationResponse('Assessment Not Found');
                }

            }else{

                return Helpers::validationResponse('User Id is required');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }


}
