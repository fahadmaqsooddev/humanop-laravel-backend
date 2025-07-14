<?php

namespace App\Http\Controllers\Api\ClientController\HumanNetwork;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\HumanNetwork\ConnectUnConnectRequest;
use App\Http\Requests\Api\Client\HumanNetwork\FollowUnFollowRequest;
use App\Models\Admin\Code\CodeDetail;
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

    public function followUnfollow(FollowUnFollowRequest $request){

        try {

            Follow::followUnFollowForApi($request);

            return Helpers::successResponse('User '.$request->type.'ed successfully');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function followers(Request $request){

        try {

            $followers = Follow::paginatedFollowers($request);

            return Helpers::successResponse('User followers', $followers, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function following(Request $request){

        try {

            $following = Follow::paginatedFollowing($request);

            return Helpers::successResponse('User followers', $following, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function connectUnconnect(ConnectUnConnectRequest $request){

        try {

            $request['user_id'] = Helpers::getUser()->id;

            Connection::connectUnConnect($request->all());

            return Helpers::successResponse('User ' . $request->type . 'ed successfully');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function users(Request $request){

        try {

            $users = User::allPaginatedClients($request);

            return Helpers::successResponse('All users', $users, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function connectionRequests(Request $request){

        try {

            $connection_requests = Connection::paginatedConnectionRequests($request);

            return Helpers::successResponse('Connection requests', $connection_requests, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function networkTutorials(){

        try {

            $tutorials = NetworkTutorial::allTutorials();

            return Helpers::successResponse('Network Tutorials', $tutorials);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function connections(Request $request){

        try {

            $connections = Connection::userPaginatedConnections($request);

            foreach ($connections as $connection){

                $connection->setAppends(['thread_id']);
            }


            return Helpers::successResponse('Connections', $connections, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function styleFeatureCodes(){

        try {

            $style_feature_codes = CodeDetail::styleAndFeatureCode();

            $style_feature_codes = array_values($style_feature_codes->toArray());

            return Helpers::successResponse('Style and Feature codes', $style_feature_codes);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function alchemyCodes(){

        try {

            $alchemy_codes = CodeDetail::alchemyCodes();

            $alchemy_codes = array_values($alchemy_codes->toArray());

            return Helpers::successResponse('Style and Feature codes', $alchemy_codes);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
