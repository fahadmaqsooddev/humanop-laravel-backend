<?php

namespace App\Http\Controllers\HAIChat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
