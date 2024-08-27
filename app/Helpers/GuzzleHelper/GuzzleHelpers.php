<?php

namespace App\Helpers\GuzzleHelper;

use App\Models\Upload\Upload;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Assessment;

class GuzzleHelpers
{

    public static function sendRequestFromGuzzle($method = null, $route_name = null, $body = [])
    {
        $authorization = \request()->header('Authorization');
        $queryArray = [
            'headers' => ['Authorization' => $authorization],
            'json' => $body
        ];
        $client = new Client(['http_errors' => false, 'timeout' => 180]);
        $route = $route_name;
        $response = $client->request($method, $route, $queryArray);
        $response_body = json_decode($response->getBody()->getContents(), true);
        return $response_body;
    }

}

