<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Code\ResultVideo;
use Illuminate\Http\Request;

class ResultVideoController extends Controller
{

    public function resultVideo()
    {
        try {

            $videos = ResultVideo::allVideos();

            return view('admin-dashboards/result-videos/index', compact('videos'));

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function editResultVideo(Request $request)
    {
        try {

            $video = ResultVideo::getSingleVideo($request['id']);

            return view('admin-dashboards/result-videos/edit-result-video', compact('video'));

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }
}
