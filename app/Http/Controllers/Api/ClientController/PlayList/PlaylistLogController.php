<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Playlist\AddPlayListRequest;
use App\Models\PlaylistLog;
use Illuminate\Http\Request;

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

            $dataArray['user_id'] = Helpers::getUser()['id'];

            PlaylistLog::addMyPlaylist($dataArray);

            return Helpers::successResponse("Add your playlist");

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function deleteMyPlaylistItem(Request $request)
    {

        try {

            $playlistId = $request['playlist_item_id'];

            if (!empty($playlistId)){

                PlaylistLog::deleteMyPlaylist($playlistId);

                return Helpers::successResponse("delete your playlist item");

            }else{

                return Helpers::validationResponse('playlist Item id is required');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

}
