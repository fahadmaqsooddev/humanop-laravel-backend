<?php

namespace App\Http\Controllers\B2BControllers;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2BOrganizationController extends Controller
{

    public function allOrganizations()
    {
        try {

            return view('b2b-dashboard.b2b-organizations.index');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function allOrganizationsUsers(Request $request)
    {
        try {
            $id = $request['id'];

            $prefer = $request['prefer'];

            return view('b2b-dashboard.b2b-organizations.b2b-users', compact('id', 'prefer'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function allB2BDeletedClients()
    {

        try {

            return view('b2b-dashboard.b2b-organizations.b2b-deleted-organizations');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

}
