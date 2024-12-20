<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function index($id = null){

        try {

            return view('client-dashboard.messages.message', compact('id'));

        }catch (\Exception $exception){

            return redirect()->back()->with('error', $exception->getMessage());
        }

    }
}
