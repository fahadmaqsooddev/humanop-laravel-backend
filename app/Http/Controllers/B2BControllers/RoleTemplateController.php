<?php

namespace App\Http\Controllers\B2BControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleTemplateController extends Controller
{
    //

    public function allRoleTemplates(){
        try {

            return view('b2b-dashboard.role-templates.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
