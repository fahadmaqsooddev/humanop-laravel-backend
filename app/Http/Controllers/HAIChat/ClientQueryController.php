<?php

namespace App\Http\Controllers\HAIChat;

use App\Http\Controllers\Controller;

class ClientQueryController extends Controller
{

    public function clientQuery()
    {
        try {

            return view('admin-dashboards.client-queries.index');

        }catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function approveQueries()
    {
        try {

            return view('admin-dashboards.approve-queries.index');

        }catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }
    }
}
