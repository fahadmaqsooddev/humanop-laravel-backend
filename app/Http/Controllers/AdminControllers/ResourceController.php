<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
    public function resources()
    {
        try {

            return view('admin-dashboards.resources.index');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_resources')->with('error', $exception->getMessage());

        }
    }

    public function mediaPlayer()
    {
        try {

            return view('admin-dashboards.media-player.index');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_resources')->with('error', $exception->getMessage());

        }
    }

}
