<?php

namespace App\Http\Controllers\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NetworkController extends Controller
{


    public function network()
    {
        try {

            return view('client-dashboard.network.index');

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function followFollowing(){

        try {

            return view('client-dashboard.network.follow-following');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function connection(){

        try {

            return view('client-dashboard.network.connection');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

}
