<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Playlist\AddPlayListRequest;
use App\Http\Requests\Api\Client\Playlist\DeletePlayListRequest;
use App\Http\Requests\Api\CLient\playlist\PlaylistItemTrackRequest;
use App\Http\Requests\Api\Client\Playlist\SortingPlaylistRequest;
use App\Models\Playlist\PlaylistItemTrack;
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

    public function playlistItemTrack(PlaylistItemTrackRequest $request)
    {

        try {

            $dataArray = $request->only((new PlaylistItemTrack)->getFillable());

            $dataArray['user_id'] = Helpers::getUser()['id'];

            $playlistItem = PlaylistItemTrack::checkOrUpdatePlaylistItem($dataArray);

            if ($playlistItem){

                return Helpers::successResponse("playlist item tracked successfully");

            }else{

                return Helpers::validationResponse("playlist item tracking is unsuccessful");
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function getPlaylistItemTrack(Request $request)
    {

        try {

            if (!empty($request['playlist_id']) && !empty($request['item_id'])){

                $playlistItem = PlaylistItemTrack::getPlaylistItem($request['playlist_id'], $request['item_id'], Helpers::getUser()['id']);

                if ($playlistItem){

                    return Helpers::successResponse("playlist item", $playlistItem);

                }else{

                    return Helpers::validationResponse("playlist item not found");
                }

            }else{

                return Helpers::validationResponse("playlist or item id is required");
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function sortingMyPlaylistItems(SortingPlaylistRequest $request)
    {

        try {

            $dataArray = $request->only($this->playlist->getFillable());

            $dataArray['user_id'] = Helpers::getUser()['id'];

            $dataArray['playlist_item_ids'] = $request['playlist_item_ids'];

            PlaylistLog::sortingMyPlaylist($dataArray);

            return Helpers::successResponse("your playlist sorting has been updated");

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
