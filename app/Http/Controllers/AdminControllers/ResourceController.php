<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function energyCenter()
    {
        try {

            return view('admin-dashboards.resources.energy_center');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_resources')->with('error', $exception->getMessage());

        }
    }

    public function masterKey()
    {
        try {

            return view('admin-dashboards.resources.master_key');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_resources')->with('error', $exception->getMessage());

        }
    }

    public function style()
    {
        try {

            return view('admin-dashboards.resources.styles');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_resources')->with('error', $exception->getMessage());

        }
    }

    public function cycle()
    {
        try {

            return view('admin-dashboards.resources.cycle');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_resources')->with('error', $exception->getMessage());

        }
    }

    public function alchemy()
    {
        try {

            return view('admin-dashboards.resources.alchemy');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_resources')->with('error', $exception->getMessage());

        }
    }
}
