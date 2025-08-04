<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResultVideoController extends Controller
{

    public function resultVideo()
    {
        try {


            return view('admin-dashboards/result-videos/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

}
