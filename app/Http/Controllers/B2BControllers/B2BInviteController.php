<?php

namespace App\Http\Controllers\B2BControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2BInviteController extends Controller
{
    //

    public function getB2BInvite(){
        try {

            return view('b2b-dashboard/b2b-invites/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }
}
