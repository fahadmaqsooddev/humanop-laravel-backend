<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class Embedding extends Component
{
    public $name,$embedding, $group_id;
    use WithFileUploads;

    protected $listeners = ['deleteEmbedding'];

    public function deleteEmbedding($id)
    {
        $embedding = HaiChatEmbedding::singleEmbedding($id);

        $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/delete_embeddings', ['folder_n' => $embedding['request_id']]);

        if ($aiReply == 1)
        {

            GroupEmbedding::deleteGroupEmbeddings($id);

            HaiChatEmbedding::deleteEmbedding($id);

            session()->flash('success', "{$embedding['name']} deleted successfully.");

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

    public function getEmbeddings()
    {
        $this->embeddings = GroupEmbedding::groupEmbeddings($this->group_id);

    }

    public function render()
    {
        $this->getEmbeddings();

        return view('livewire.admin.hai-chat.embedding');
    }
}
