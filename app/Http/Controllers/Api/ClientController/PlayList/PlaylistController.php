<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Playlist\NewPlaylistRequest;
use App\Models\Playlist\Playlist;

class PlaylistController extends Controller
{

    protected $playlist = null;

    public function __construct(Playlist $playlist)
    {
        $this->middleware('auth:api');

        $this->playlist = $playlist;

    }

    public function myPlaylists()
    {
        try {
            $playlists = $this->playlist->myPlaylists();

            $myPlaylists = [];

            foreach ($playlists as $playlist) {
                $mergedResourceItems = [];
                $mergedShopItems = [];

                foreach ($playlist['playlist'] as $playlistLog) {

                    if (!empty($playlistLog['resourceItems'])) {
                        foreach ($playlistLog['resourceItems'] as $item) {
                            $mergedResourceItems[] = $item;
                        }
                    }

                    // Merge shop items
                    if (!empty($playlistLog['shopItems'])) {
                        foreach ($playlistLog['shopItems'] as $item) {
                            $mergedShopItems[] = $item;
                        }
                    }
                }

                $myPlaylists[] = [
                    'id' => $playlist['id'],
                    'title' => $playlist['title'],
                    'description' => $playlist['description'],
                    'resource_items' => $mergedResourceItems,
                    'shop_items' => $mergedShopItems,
                ];
            }

            return Helpers::successResponse('All My Playlists', $myPlaylists);

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function newPlaylist(NewPlaylistRequest $request)
    {

        try {

            $dataArray = $request->only($this->playlist->getFillable());

            $dataArray['user_id'] = Helpers::getUser()['id'];

            Playlist::newPlaylist($dataArray);

            return Helpers::successResponse("Add your playlist");

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }
}
