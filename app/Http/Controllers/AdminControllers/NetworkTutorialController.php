<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\NetworkTutorial\NetworkTutorial;

class NetworkTutorialController extends Controller
{
    protected $tutorial = null;

    public function __construct(NetworkTutorial $tutorial)
    {
        $this->tutorial = $tutorial;
    }

    public function networkTutorials()
    {
        try {

            return view('admin-dashboards.network-tutorial.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
