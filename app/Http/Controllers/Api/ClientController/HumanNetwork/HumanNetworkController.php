<?php

namespace App\Http\Controllers\Api\ClientController\HumanNetwork;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\HumanNetwork\ConnectUnConnectRequest;
use App\Http\Requests\Api\Client\HumanNetwork\FollowUnFollowRequest;
use App\Models\Client\Connection\Connection;
use App\Models\Client\Follow\Follow;
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

    public function connections(Request $request){

        try {

            $connections = Connection::userPaginatedConnections($request);

            return Helpers::successResponse('Connections', $connections, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
