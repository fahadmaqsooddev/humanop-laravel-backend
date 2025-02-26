<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\B2B\listTasksRequest;
use App\Models\B2B\RoleTemplate;
use App\Models\B2B\TaskResponsibilities;
use Illuminate\Http\Request;

class RoleController extends Controller
{
//    protected $user;

    public function __construct()
    {

        $this->middleware('auth:api');

    }

    public static function listAdminRole()
    {

        try {

            $tasks = RoleTemplate::allTemplate();

            return Helpers::successResponse('admin Role Template lists', $tasks);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public static function listRoleTasks(listTasksRequest $request)
    {

        try {

            $tasks = TaskResponsibilities::getTasksResponsbilities($request['role_id']);

            return Helpers::successResponse('admin Role Tasks and Responsibilities lists', $tasks);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }
}
