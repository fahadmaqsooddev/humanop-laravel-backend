<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\B2B\addRoleRequest;
use App\Http\Requests\B2B\listTasksRequest;
use App\Models\B2B\B2BTaskAndResponsibilities;
use App\Models\B2B\RoleTemplate;
use App\Models\B2B\TaskResponsibilities;
use App\Models\User;

class RoleController extends Controller
{
    protected $taskAndResponsibility;

    public function __construct(User $taskAndResponsibility)
    {

        $this->middleware('auth:api');

        $this->taskAndResponsibility = $taskAndResponsibility;
    }


    public static function addRole(addRoleRequest $request)
    {

        try {

            $taskAndResponsibility = new B2BTaskAndResponsibilities();

            $dataArray = $request->only($taskAndResponsibility->getFillable());

            B2BTaskAndResponsibilities::createTaskAndResponsibility($dataArray);

            return Helpers::successResponse('Task and Responsbility created successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

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
