<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use App\Models\HAIChai\HaiChatSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class Embedding extends Component
{
    public $name,$embedding,$bot_name,$request_id,$button_status, $query, $chunks = [], $groups, $group_id,
        $embeddings = [], $embedding_id, $active_embeddings = [], $activeGroups, $embedding_search,
        $showDropdownMenu = false, $group_search, $showGroupDropdownMenu = false, $groupButtonText = 'Select Group';
//    public $button_status_display = false;
//    public $selected_embedding = "SELECT AN EMBEDDING";
    use WithFileUploads;

    public function mount($bot_name)
    {
        $this->bot_name = $bot_name;
    }


    protected $rules = [
        'name' => 'required',
        'embedding' => 'required|file|mimes:txt,pdf', // Corrected 'memes' to 'mimes'
    ];

    protected $messages = [
        'name.required' => 'The Name field is required.', // Corrected message to use proper text
        'embedding.required' => 'The Embedding field is required.', // Corrected message to use proper text
        'embedding.mimes' => 'The Embedding must be a file of type: txt, pdf.', // Added message for mime type validation
    ];

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
            if ($this->name) {
                $multipart[] = [
                    'name'     => 'name',
                    'contents' => $this->name
                ];
            }
            // Send the request
            $aiReply = $this->sendRequestFromGuzzle('POST', 'http://18.234.162.68:8000/upload_embedding', [
                'multipart' => $multipart
            ]);
            if(!empty($aiReply['request_id'])){
               $embedding = HaiChatEmbedding::createEmbedding($this->name,$aiReply['request_id']);
                HaiChatActiveEmbedding::createActiveEmbedding($this->bot_name, $aiReply['request_id']);
               if($embedding){
                   session()->flash('success', "Created Successfully.");
               }else{
                   session()->flash('error', "Something went wrong.");
               }
            }
        }catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function mount(){

        $this->is_pine_cone = \request()->input('pine_cone_database', false);
    }

    public function searchEmbedding()
    {
        try {

            $this->reset('showDropdownMenu','showGroupDropdownMenu');

            $this->validate(['query' => 'required'],['query.required' => 'Query is required']);

            $embedding = HaiChatActiveEmbedding::getChatActiveEmbedding($this->bot_name);

            $chat_bot_id = Chatbot::getChatFromVendorName($this->bot_name)->id ?? null;

            $chatSetting = HaiChatSetting::getHaiChatSetting($chat_bot_id);

            $aiReply = $this->sendSearchEmbeddingRequestFromGuzzle('post', 'http://18.234.162.68:8000/search_embeddings', ['query' => $this->query, 'file_name' => $embedding['file_name'], 'total_chunks' => $chatSetting['chunk']]);

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

//            HaiChaiChunk::checkAndUpdateHaiChunks($aiReply, '',$this->bot_name);

            $this->query = '';

            $this->emit('closeModel');

        }catch (\Exception $exception)
        {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function sendSearchEmbeddingRequestFromGuzzle($method = null, $route_name = null, $body = [])
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

//    public function changeEmbeddingSelect($name,$request_id){
//        $this->selected_embedding = $name;
//        $this->request_id = $request_id;
//        $activeEmbedding = HaiChatActiveEmbedding::singleActiveEmbedding($this->request_id, $this->bot_name);
//        if($activeEmbedding){
//            $this->button_status = 'Disconnect';
//        }else{
//            $this->button_status = 'Connect';
//        }
//        $this->button_status_display = true;
//    }

    public function changeEmbeddingStatus($request_id){

        $this->request_id = $request_id;

        if($this->request_id && $this->bot_name){
            $activeEmbedding = HaiChatActiveEmbedding::singleActiveEmbedding($this->request_id, $this->bot_name);
            if($activeEmbedding){
                $aiReply = $this->sendEmbeddingRequestFromGuzzle('post', 'http://18.234.162.68:8000/check-embeddings', ['folder_n' => $this->request_id]);
                 if($aiReply){
                     if($aiReply['exists']){
                         HaiChatActiveEmbedding::deleteActiveEmbedding($this->request_id);
                         $this->button_status = 'Connect';
                     }
                 }
            }else{
                $aiReply = $this->sendEmbeddingRequestFromGuzzle('post', 'http://18.234.162.68:8000/check-embeddings', ['folder_n' => $this->request_id]);
                if($aiReply){
                    if($aiReply['exists']) {
                        HaiChatActiveEmbedding::createActiveEmbedding($this->bot_name, $this->request_id);
                        $this->button_status = 'Disconnect';
                    }
                }
            }

            $this->embeddings = HaiChatEmbedding::embeddings($this->group_id, $this->bot_name, $this->embedding_search);
        }

        $this->showDropdownMenu = true;

    }

    public function sendRequestFromGuzzle($method = null, $route_name = null, $body = [])
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

    public function sendEmbeddingRequestFromGuzzle($method = null, $route_name = null, $body = [])
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

//    public function getEmbeddings()
//    {
//        $request_ids = HaiChatActiveEmbedding::allRequestIds($this->bot_name);
//        $this->embeddings = HaiChatEmbedding::allEmbeddingsExcept($request_ids);
//    }

//    public function getActiveEmbeddings()
//    {
//        $this->active_embeddings = HaiChatActiveEmbedding::allActiveEmbeddings($this->bot_name);
//    }

//    public function getChunks()
//    {
//        $this->chunks = HaiChaiChunk::getHaiChunk( '',$this->bot_name, $this->query);
//    }

    public function updateGroupId($id, $name){

        $this->embedding_id = "";

        $this->group_id = $id;

        $this->groupButtonText = $name;

//        $this->button_status_display = false;

//        $this->embeddings = GroupEmbedding::groupEmbeddings($id);

//        $this->active_embeddings = HaiChatEmbedding::activeEmbeddings($id, $this->bot_name);

        $this->embeddings = HaiChatEmbedding::embeddings($id, $this->bot_name, $this->embedding_search);

        $this->emit('makeEmbeddingDownDownScrollable');

        $this->showDropdownMenu = false;

//        dd($this->embeddings);

//        $this->embeddings = array_merge($this->active_embeddings->toArray());
    }

//    public function updatedEmbeddingId($request_id){
//
//        if ($request_id){
//
//            $activeEmbedding = HaiChatActiveEmbedding::singleActiveEmbedding($request_id, $this->bot_name);
//
//            if($activeEmbedding){
//
//                $this->button_status = 'Disconnect';
//
//            }else{
//
//                $this->button_status = 'Connect';
//            }
//
//            $this->button_status_display = true;
//
//            $this->request_id = $request_id;
//
//        }else{
//
//            $this->button_status_display = false;
//        }
//
//    }

    public function updatedEmbeddingSearch($value){

        $this->embedding_search = $value;

        $this->embeddings = HaiChatEmbedding::embeddings($this->group_id, $this->bot_name, $this->embedding_search);

        $this->emit('makeEmbeddingDownDownScrollable');

        $this->showDropdownMenu = true;
    }

    public function updatedGroupSearch($value){

        $this->group_search = $value;

        $this->showGroupDropdownMenu = true;

        $this->emit('makeGroupDownDownScrollable');
    }

    public function render()
    {
        $this->groups = EmbeddingGroup::groups($this->bot_name, $this->group_search);

//        $this->activeGroups = EmbeddingGroup::activeGroups();

        return view('livewire.admin.hai-chat.setting.embedding');
    }
}
