<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Playlist\AddPlayListRequest;
use App\Models\PlaylistLog;

class PlaylistLogController extends Controller
{

    protected $playlist = null;

    public function __construct(PlaylistLog $playlist)
    {
        $this->middleware('auth:api');

        $this->playlist = $playlist;

    }

    public function addMyPlaylist(AddPlayListRequest $request)
    {

        try {

            $dataArray = $request->only($this->playlist->getFillable());

            PlaylistLog::addMyPlaylist($dataArray);

            return Helpers::successResponse("Add your playlist");

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

}
