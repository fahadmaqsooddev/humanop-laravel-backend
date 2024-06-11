<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\DailyTip\DailyTip;

class TipController extends Controller
{

    public function index()
    {
        try {

            $tips = DailyTip::getTip();

            return view('admin-dashboards.daily-tips.index', compact('tips'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function create()
    {
        try {

            return view('admin-dashboards.daily-tips.create');

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
