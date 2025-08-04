<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Playlist\AddPlayListRequest;
use App\Models\Playlist\Playlist;
use App\Models\Upload\Upload;
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

                $myPlaylists[] = [
                    'id' => $playlist['id'],
                    'title' => $playlist['title'],
                    'description' => $playlist['description'],
                    'audio_url' => $playlist['audio_url']['path'],
                ];

            }

            return  Helpers::successResponse('all My Playlists', $myPlaylists);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function addMyPlaylist(AddPlayListRequest $request)
    {

        try {

            $dataArray = $request->only($this->playlist->getFillable());

            $audioFile = $request->file('audio_file');

            if ($audioFile && in_array($audioFile->extension(), ['mp3', 'wav', 'mpeg', 'aac', 'ogg', 'oga', 'm4a', 'flac', 'alac', 'wma', 'amr', 'midi', 'mid', 'opus', 'aiff', 'aif'])) {

                $uploadId = Upload::uploadFile($audioFile, '', '', 'audio');

                $dataArray['audio_id'] = $uploadId;

                $dataArray['user_id'] = Helpers::getUser()['id'];

                Playlist::addPlaylist($dataArray);

                return Helpers::successResponse("Add your playlist");
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }
}
