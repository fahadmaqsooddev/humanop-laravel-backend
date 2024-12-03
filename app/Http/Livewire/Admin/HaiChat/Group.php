<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HaiChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class Group extends Component
{

    use WithFileUploads;

    protected $listeners = ['deleteEmbedding'];

    public $groups, $name, $group_ids = [], $embedding_name, $embedding, $embeddings;

    protected $rules = [
        'embedding_name' => 'required',
        'embedding' => 'required|file|mimes:txt,pdf', // Corrected 'memes' to 'mimes'
        'group_ids' => 'required|array',
        'group_ids.*' => 'required|exists:embedding_groups,id',
    ];

    protected $messages = [
        'embedding_name.required' => 'The Name field is required.', // Corrected message to use proper text
        'embedding.required' => 'The Embedding field is required.', // Corrected message to use proper text
        'embedding.mimes' => 'The Embedding must be a file of type: txt, pdf.', // Added message for mime type validation
    ];

    public function render()
    {
        $this->groups = EmbeddingGroup::allGroups();

        $this->embeddings = HaiChatEmbedding::allEmbeddings();

        return view('livewire.admin.hai-chat.group');
    }

    public function createEmbedding(){
        try {

            $this->validate();
            // Get the real path of the uploaded file
            $filePath = $this->embedding->getRealPath();

            // Prepare the multipart data for sending
            $multipart = [
                [
                    'name'     => 'file', // Field name expected by the server
                    'contents' => file_get_contents($filePath), // File contents
                    'filename' => basename($filePath) // Optional: the file name
                ]
            ];

            // Include other form data like 'name' (if provided)
            if ($this->embedding_name) {
                $multipart[] = [
                    'name'     => 'name',
                    'contents' => $this->embedding_name
                ];
            }
            // Send the request
            $aiReply = $this->sendCreateRequestFromGuzzle('POST', 'http://18.234.162.68:8000/upload_embedding', [
                'multipart' => $multipart
            ]);
            if(!empty($aiReply['request_id'])){
                $embedding = HaiChatEmbedding::createEmbedding($this->embedding_name,$aiReply['request_id']);

                if($embedding){

                    if (count($this->group_ids) > 0){

                        GroupEmbedding::addOrUpdateGroupIds($this->group_ids, $embedding->id);
                    }

                    session()->flash('success', "Created Successfully.");

                    $this->reset('embedding_name','embedding','group_ids');

                }else{

                    session()->flash('error', "Something went wrong.");
                }
            }
        }catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function sendCreateRequestFromGuzzle($method = null, $route_name = null, $body = [])
    {
        $authorization = Request::header('Authorization');

        // Prepare the query array with headers and multipart data
        $queryArray = [
            'headers' => [
                'Authorization' => $authorization, // Authorization header
            ],
            'multipart' => $body['multipart'] // Send multipart data
        ];

        // Initialize Guzzle client
        $client = new Client(['http_errors' => false, 'timeout' => 180]);

        // Send the request
        $response = $client->request($method, $route_name, $queryArray);

        // Get and decode the response body
        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }

    public function createGroup(){

        $this->validate(['name' => 'required|max:200'],['name.required' => 'Group name is required']);

        EmbeddingGroup::createEmbeddingGroup($this->name);

        session()->flash('success', "Created Successfully.");

        $this->emit('closeCreateGroupModal');

        $this->reset('name');
    }

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
}
