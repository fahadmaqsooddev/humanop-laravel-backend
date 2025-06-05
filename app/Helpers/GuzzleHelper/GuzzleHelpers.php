<?php

namespace App\Helpers\GuzzleHelper;

use App\Helpers\Helpers;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        $route = config('chat.staging_api_urls') . $route_name;

        $response = $client->request($method, $route, $queryArray);

        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }

    public static function createOpenAiEmbedding($file)
    {

        $fileText = file_get_contents($file->getRealPath());

        $tokenCount = self::countTokens($fileText);

        $yourApiKey = config('openAi.credentials.api');

        $client = \OpenAI::client($yourApiKey);

        if ($tokenCount > 8000) {

            $embeddingArray = [];

            $texts = Helpers::stringFromPdfOrTextFile($fileText);

            foreach ($texts as $text) {

                $response = $client->embeddings()->create([
                    'model' => 'text-embedding-3-small',
                    'input' => $text,
                ]);

                $response = $response->toArray();

                foreach ($response['data'] as $embeddingVector) {

                    array_push($embeddingArray, $embeddingVector['embedding']);

                }

                return $embeddingArray;

            }

        } else {

            $response = $client->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $fileText,
            ]);

            $response = $response->toArray();

            foreach ($response['data'] as $embeddingVector) {

                return $embeddingVector['embedding'];

            }

        }

    }

    public static function countTokens(string $text, string $model = 'text-embedding-3-small'): ?int
    {

        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);

        try {

            $response = $client->post('tokenizer', [
                'json' => [
                    'text' => $text,
                    'model' => $model,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['token_count'] ?? null;

        } catch (\Exception $e) {
            // Log the error or handle it as needed.
            Log::error('Error counting tokens: ' . $e->getMessage());

            return null;
        }
    }

    public static function getStripeReceiptPdf($link = null, $method = "GET")
    {

        $client = new Client(['http_errors' => false, 'timeout' => 180]);

        $response = $client->request($method, $link);

        $pdf = $response->getBody()->getContents();

        return $pdf;

    }

    public static function sendRequestFromGuzzleForDojo($method = null, $route_name = null, $body = [])
    {

        $authorization = \request()->header('Authorization');

        $queryArray = [
            'headers' => ['Authorization' => $authorization],
            'json' => $body
        ];

        $client = new Client(['http_errors' => false, 'timeout' => 180]);

//        $route = "http://3.87.21.19:8000/" . $route_name;
        $route = "http://18.206.155.155:8000/" . $route_name;

        $response = $client->request($method, $route, $queryArray);

        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }

    public static function sendRequestFromGuzzleForDojoExport($method = null, $route_name = null, $body = [])
    {

        $authorization = \request()->header('Authorization');

        $queryArray = [
            'headers' => ['Authorization' => $authorization],
            'json' => $body
        ];

        $client = new Client(['http_errors' => false, 'timeout' => 180]);

//        $route = "http://3.87.21.19:8000/" . $route_name;
        $route = "http://18.206.155.155:8000/" . $route_name;

        $response = $client->request($method, $route, $queryArray);

        return $response->getBody()->getContents();
    }

    public static function sendRequestFromGuzzleForNewHai($method = null, $route_name = null, $body = [])
    {

        $authorization = \request()->header('Authorization');

        $queryArray = [
            'headers' => ['Authorization' => $authorization],
            'json' => $body
        ];

        $client = new Client(['http_errors' => false, 'timeout' => 180]);

        $route = config('chat.dev_new_api_urls') . $route_name;

        $response = $client->request($method, $route, $queryArray);

        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }

}

