<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Podcast\Podcast;

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

    public function createPodcast()
    {
        try {

            return view('admin-dashboards.podcast.create');

        } catch (\Exception $exception) {

            return redirect()->route('podcast')->with('error', $exception->getMessage());

        }
    }

    public function editPodcast($id)
    {
        try {

            $podcast = Podcast::singlePodcast($id);

            return view('admin-dashboards.podcast.edit', compact('podcast'));

        } catch (\Exception $exception) {

            return redirect()->route('podcast')->with('error', $exception->getMessage());

        }
    }

}
