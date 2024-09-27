<?php

namespace App\Http\Controllers\Practitioner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PractitionerController extends Controller
{


    public function allPractitioners()
    {
        try {

            return view('admin-dashboards.practitioners.practitioner');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
