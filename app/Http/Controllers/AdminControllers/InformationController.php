<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformationController extends Controller
{


    public function getInfo()
    {
        try {

            return view('admin-dashboards/information-icons/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }
}
