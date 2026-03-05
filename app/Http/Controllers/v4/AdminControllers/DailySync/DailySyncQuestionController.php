<?php

namespace App\Http\Controllers\v4\AdminControllers\DailySync;

use App\Http\Controllers\Controller;
use App\Models\v4\Admin\DailySync\DailySyncQuestion;
use Illuminate\Http\Request;

class DailySyncQuestionController extends Controller
{
    public function dailySyncQuestion()
    {
        try {

            return view('v4.admin-dashboards.daily-sync-question.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }

    }

}
