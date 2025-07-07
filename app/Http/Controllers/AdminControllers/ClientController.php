<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;

class ClientController extends Controller
{

    public function getClientInvite()
    {
        try {

            return view('admin-dashboards/client-invites/index');

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }
}
