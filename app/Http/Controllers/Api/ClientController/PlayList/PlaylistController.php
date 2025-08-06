<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Playlist\NewPlaylistRequest;
use App\Models\Playlist\Playlist;
use Illuminate\Http\Request;

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
                $mergedPodcastItems = [];

                foreach ($playlist['playlist'] as $playlistLog) {

                    if (!empty($playlistLog['resourceItems'])) {
                        foreach ($playlistLog['resourceItems'] as $item) {
                            $mergedResourceItems[] = $item;
                        }
                    }

                    if (!empty($playlistLog['shopItems'])) {
                        foreach ($playlistLog['shopItems'] as $item) {
                            $mergedShopItems[] = $item;
                        }
                    }

                    if (!empty($playlistLog['podcastItems'])) {
                        foreach ($playlistLog['podcastItems'] as $item) {
                            $mergedPodcastItems[] = $item;
                        }
                    }
                }

                $myPlaylists[] = [
                    'id' => $playlist['id'],
                    'title' => $playlist['title'],
                    'description' => $playlist['description'],
                    'resource_items' => $mergedResourceItems,
                    'shop_items' => $mergedShopItems,
                    'podcast_items' => $mergedPodcastItems,
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

    public static function deleteMyPlaylists(Request $request)
    {

        try {

            $playlistId = $request['playlist_id'];

            if (!empty($playlistId)){

                Playlist::deletePlaylist($playlistId);

                return Helpers::successResponse("Delete your playlist");

            }else{

                return Helpers::validationResponse('Playlist id is required');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }
}
