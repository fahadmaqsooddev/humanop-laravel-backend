<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;

class DailyTipController extends Controller
{
    public function allDailyTip()
    {
        try {

            return view('admin-dashboards.daily-tip.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}

