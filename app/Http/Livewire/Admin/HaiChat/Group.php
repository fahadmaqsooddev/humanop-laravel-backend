<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Helpers\Helpers;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class Group extends Component
{

    use WithFileUploads;

    protected $listeners = ['deleteEmbedding'];

    public $groups, $name, $embedding_ids = [], $embedding_name, $embedding, $embeddings, $embedding_id, $group_ids, $fileInputId;

    protected $rules = [
        'embedding_name' => 'required|max:50',
        'embedding' => 'required|file|mimes:txt,pdf', // Corrected 'memes' to 'mimes'
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

                    session()->flash('embedding_success', "Embedding created successfully.");

                    $this->emit('closeCreateEmbeddingModal');

                    $this->reset('embedding_name');

                    $this->fileInputId++; // this is just for remove placeholder for file input field

                    $this->embedding = null;

                }else{

                    session()->flash('embedding_error', "Something went wrong.");
                }
            }
        }catch (\Illuminate\Validation\ValidationException $exception){

            session()->flash('embedding_errors', $exception->validator->errors()->getMessages());

        } catch (\Exception $exception) {

            session()->flash('embedding_error', $exception->getMessage());
        }

        $this->emit('closeAlert');
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

        try {

            $this->validate([

                'name' => 'required|max:20',
                'embedding_ids' => 'required|array',
                'embedding_ids.*' => 'required|exists:hai_chat_embeddings,id',

            ], [
                    'name.required' => 'Group name is required'
                ]
            );

            $group = EmbeddingGroup::createEmbeddingGroup($this->name);

            if (count($this->embedding_ids) > 0){

                GroupEmbedding::addOrUpdateGroupIds($this->embedding_ids, $group->id);
            }

            session()->flash('success', "Group created successfully.");

            $this->emit('closeCreateGroupModal');

            $this->reset('name','embedding_ids');

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('success', $exception->getMessage());
        }

        $this->emit('closeAlert');
    }

    public function deleteEmbedding($id)
    {
        $embedding = HaiChatEmbedding::singleEmbedding($id);

        $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/delete_embeddings', ['folder_n' => $embedding['request_id']]);

        if ($aiReply == 1)
        {

            GroupEmbedding::deleteGroupEmbeddings($id);

            HaiChatEmbedding::deleteEmbedding($id);

            session()->flash('embedding_deleted', "{$embedding['name']} deleted successfully.");

            $this->emit('closeAlert');

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

    public function resetValidationError(){

        $this->resetValidation();
    }

    public function setEmbeddingId($embedding_id){

        $this->group_ids = GroupEmbedding::embeddingGroups($embedding_id);

        $this->embedding_id = $embedding_id;
    }

    public function addEmbeddingToGroups(){

        try {

            $this->validate(['group_ids' => 'required']);

            GroupEmbedding::addOrUpdateEmbeddingIds($this->group_ids, $this->embedding_id);

            session()->flash('embedding_group_success', 'Embedding are added into groups');

            $this->emit('closeAlert');

            $this->group_ids = GroupEmbedding::embeddingGroups($this->embedding_id);

        }catch (ValidationException $validationException){

            session()->flash('embedding_group_errors', $validationException->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('embedding_group_error', $exception->getMessage());
        }
    }
}
