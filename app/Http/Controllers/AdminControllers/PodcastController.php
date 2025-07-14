<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;

class PodcastController extends Controller
{

    public function podcast()
    {
        try {

            return view('admin-dashboards.podcast.index');

        } catch (\Exception $exception) {

            return redirect()->route('podcast')->with('error', $exception->getMessage());

        }
    }
}
