<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use App\Models\HAIChai\QueryAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\TipRecord;
use App\Models\Admin\Podcast\Podcast;

class ClientController extends Controller
{

    public function index()
    {
        try {

            $podcast = Podcast::getPodcast();

            $user = Auth::user();

            $tip_records = TipRecord::getTipRecord();

            $tip = DailyTip::getSingleTip($tip_records);

            $admin_answer = QueryAnswer::userQueryAnswer();

            return view('client-dashboard.dashboard.index', compact('user', 'tip', 'podcast', 'admin_answer'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
