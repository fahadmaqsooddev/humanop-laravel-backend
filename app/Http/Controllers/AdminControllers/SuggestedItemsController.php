<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuggestedItemsController extends Controller
{

    public function suggestedItems()
    {
        try {

            return view('admin-dashboards.suggested-items.index');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_resources')->with('error', $exception->getMessage());

        }
    }

}
