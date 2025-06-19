<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\EmbeddingSetting;
use App\Models\HAIChai\HaiChaiChunk;
use App\Models\HAIChai\HaiChatEmbedding;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class SearchEmbedding extends Component
{
    public $name, $query, $chunks = [];

    protected $rules = [
        'query' => 'required',
    ];

    protected $messages = [
        'query.required' => 'Query is required',
    ];
    public function submitForm()
    {
        try {
            $this->validate();

            $embedding = HaiChatEmbedding::getEmbeddingByName($this->name);;

            $setting = EmbeddingSetting::getEmbeddingSetting($this->name);

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

            $body = ['query' => $this->query, 'file_name' => $embedding, 'total_chunks' => $setting['chunk'] ?? 2, 'loc' => $subFolder];

            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'search_embeddings', $body);

//            $aiReply = $this->sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/search_embeddings', ['query' => $this->query, 'file_name' => $embedding, 'total_chunks' => $setting['chunk'] ?? 2, 'loc' => $subFolder]);

            $i = 0;

            if ($aiReply['retrieved_docs'] ?? false){

                foreach ($aiReply['retrieved_docs'] as $retrieved)
                {
                    foreach ($retrieved as $da)
                    {
                        $data = [
                            'embedding' => $embedding,
                            'query' => $this->query,
                            'retrieved_docs' => $da
                        ];

                        $this->chunks[$i] = $data;

                        $i++;
                    }
                }

            }

//            HaiChaiChunk::checkAndUpdateHaiChunks($aiReply, $this->name);

            $this->query = '';

            $this->emit('closeModel');

        }catch (\Exception $exception)
        {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function sendRequestFromGuzzle($method = null, $route_name = null, $body = [])
    {

        $authorization = Request::header('Authorization');

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

//    public function getChunks()
//    {
//        $this->chunks = HaiChaiChunk::getHaiChunk($this->name);
//    }

    public function render()
    {

        return view('livewire.admin.hai-chat.search-embedding');
    }
}
