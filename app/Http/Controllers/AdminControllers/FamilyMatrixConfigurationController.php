<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FamilyMatrixConfigurationController extends Controller
{
    public function familyMatrixConfiguration()
    {
        try {

            return view('admin-dashboards.family-matrix-configuration.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
