<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\VersionControl\Version;
use Illuminate\Http\Request;

class VersionController extends Controller
{

    public function getLatestVersion()
    {
        try {

            $version = Version::getLatestVersion();

            return view('client-dashboard/version/index', compact('version'));

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function getVersion()
    {
        try {

            return view('admin-dashboards/version-control/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }


    public function createVersion(){
        try {

            return view('admin-dashboards/version-control/create-version-control');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }
    public function EditVersion(Request $request){
        try {
            $id=$request['id'];
             
            

            return view('admin-dashboards/version-control/create-version-control',compact('id'));

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

}
