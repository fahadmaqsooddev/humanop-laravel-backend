<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Podcast\Podcast;

class PodcastController extends Controller
{

    public function podcast()
    {
        try {

            $podcast = Podcast::getPodcast();

            return view('admin-dashboards.podcast.index', compact('podcast'));

        } catch (\Exception $exception) {

            return redirect()->route('podcast')->with('error', $exception->getMessage());

        }
    }
}
