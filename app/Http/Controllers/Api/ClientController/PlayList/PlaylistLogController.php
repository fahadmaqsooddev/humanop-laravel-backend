<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Playlist\AddPlayListRequest;
use App\Http\Requests\Api\Client\Playlist\DeletePlayListRequest;
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

    public function deleteMyPlaylistItem(DeletePlayListRequest $request)
    {
        try {
            $dataArray = $request->all();

            $dataArray['user_id'] = Helpers::getUser()['id'];

            $deleted = PlaylistLog::deleteMyPlaylist($dataArray);

            if ($deleted) {

                return Helpers::successResponse("Deleted your playlist item successfully");

            } else {

                return Helpers::validationResponse("Playlist item not found or could not be deleted");

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

}
