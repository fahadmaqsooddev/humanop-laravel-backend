<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\TipRecord;

class ClientController extends Controller
{

    public function index()
    {
        try {
            dd(1);

            $user = Auth::user();

            $tip_records = TipRecord::getTipRecord();

            $tip = DailyTip::getSingleTip($tip_records);

            return view('client-dashboard.dashboard.index', compact('user', 'tip'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
