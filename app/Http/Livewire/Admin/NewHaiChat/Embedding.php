<?php

namespace App\Http\Livewire\Admin\NewHaiChat;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Embedding extends Component
{
    public $name,$embedding, $group_id, $fileInputId, $group_ids, $groups, $embedding_name,
        $updateId, $updateEmbeddingName, $updateEmbeddingText;
    use WithFileUploads;

    protected $listeners = ['deleteEmbedding'];

    public function deleteEmbedding($id)
    {
        $embedding = HaiChatEmbedding::singleEmbedding($id);

        $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

        $body = ['folder_n' => $embedding['request_id'], 'loc' => $subFolder];

        $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'delete_embeddings', $body);

//        $aiReply = $this->sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/delete_embeddings', ['folder_n' => $embedding['request_id'], 'loc' => $subFolder]);

        if ($aiReply)
        {

//            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev/' : env("APP_ENV");

//            Storage::disk('s3')->delete($subFolder . $embedding->request_id . "-embd.txt");
//
//            Storage::disk('s3')->delete($subFolder . $embedding->request_id . "-embd.txt");

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

    public function createEmbedding(){
        try {

            $this->validate(
                [
                    'embedding_name' => 'required|max:50',
                    'embedding' => 'required|file|mimes:txt', // Corrected 'memes' to 'mimes'
                ],
                [
                    'embedding_name.required' => 'The Name field is required.', // Corrected message to use proper text
                    'embedding.required' => 'The Embedding field is required.', // Corrected message to use proper text
                    'embedding.mimes' => 'The Embedding must be a file of type: txt.', // Added message for mime type validation
                ]
            );

//            $file = $this->embedding;
//
//            $fileId = Str::uuid();
//
//            $embedding = GuzzleHelpers::createOpenAiEmbedding($this->embedding);
//
//            $filename = $fileId . '.' . $file->getClientOriginalExtension();
//
//            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev/' : env("APP_ENV") . '/';
//
//            $path = $subFolder ? $subFolder . $filename : $filename;
//
//            Storage::disk('s3')->put($path, file_get_contents($file->getRealPath()));
//
//            $embeddingPath = $subFolder . $fileId . '-embd.txt';
//
//            Storage::disk('s3')->put($embeddingPath, json_encode($embedding));
//
//            $embedding = HaiChatEmbedding::createEmbedding($this->embedding_name,$fileId);
//
//            if($embedding){
//
//                GroupEmbedding::addOrUpdateEmbeddingIds([$this->group_id], $embedding->id);
//
//                session()->flash('embedding_success', "Embedding created successfully.");
//
//                $this->emit('closeCreateEmbeddingModal');
//
//                $this->reset('embedding_name');
//
//                $this->fileInputId++; // this is just for remove placeholder for file input field
//
//                $this->embedding = null;
//
//            }else{
//
//                session()->flash('embedding_error', "Something went wrong.");
//            }

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
            $aiReply = $this->sendCreateRequestFromGuzzle('POST', 'http://54.227.7.149:8000/upload_embedding', [
                'multipart' => $multipart
            ]);

            if(!empty($aiReply['request_id'])){

                $embedding = HaiChatEmbedding::createEmbedding($this->embedding_name,$aiReply['request_id']);

                if($embedding){

                    GroupEmbedding::addOrUpdateEmbeddingIds([$this->group_id], $embedding->id);

                    session()->flash('embedding_success', "Embedding created successfully.");

                    $this->emit('closeCreateEmbeddingModal');

                    $this->reset('embedding_name','group_ids');

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

        $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

        $filePath = $this->embedding->getRealPath();

        // Prepare the query array with headers and multipart data
        $queryArray = [
            'headers' => [
                'Authorization' => $authorization, // Authorization header
            ],
            'multipart' => [
                [
                    'name'     => 'file', // Field name expected by the server
                    'contents' => file_get_contents($filePath), // File contents
                    'filename' => basename($filePath) // Optional: the file name
                ],
                [
                    'name' => "loc",
                    'contents' => $subFolder
                ]
            ]
        ];

        // Initialize Guzzle client
        $client = new Client(['http_errors' => false, 'timeout' => 180]);

//        $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");
//
//        $route_name = $route_name . "?loc=" . $subFolder;

        // Send the request
        $response = $client->request($method, $route_name, $queryArray);

        // Get and decode the response body
        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }

    public function editEmbedding($id){

        if ($id){

            $this->updateId = $id;

            $embedding = HaiChatEmbedding::whereId($id)->first();

            $this->updateEmbeddingName = $embedding->name ?? null;

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? "dev" : env("APP_ENV");

            $body = ["request_id" => $embedding->request_id, "loc" => $subFolder];

            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'get_file_text', $body);

//            $aiReply = $this->sendRequestFromGuzzle('post','http://54.227.7.149:8000/get_file_text',$body);

            if(isset($aiReply['text_content'])){

                $this->updateEmbeddingText = $aiReply['text_content'];
            }

//            $exists = Storage::disk('s3')->exists($subFolder . $embedding->request_id . '/' . $embedding->request_id . ".txt");
//
//            if ($exists){
//
//                $this->updateEmbeddingText = Storage::disk('s3')->get($subFolder . $embedding->request_id . ".txt");
//
//            }else{
//
//                $this->updateEmbeddingText = null;
//            }

            $this->emit('openEditModal');
        }
    }

    public function updateEmbedding(){

        try {

            $this->validate(
                [
                    'updateEmbeddingName' => 'required|max:50',
                    'updateEmbeddingText' => 'required|string',
                ],
                [
                    'embedding_name.required' => 'The Name field is required.', // Corrected message to use proper text
                    'embedding.required' => 'The Embedding field is required.', // Corrected message to use proper text
                    'embedding.mimes' => 'The Embedding must be a file of string.',
                ]
            );

            $embedding = HaiChatEmbedding::whereId($this->updateId)->first();

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

            $body = ['loc' => $subFolder, 'request_id' => $embedding->request_id, 'text' => $this->updateEmbeddingText];

            GuzzleHelpers::sendRequestFromGuzzle('post', 'update_embedding', $body);

//            $this->sendRequestFromGuzzle('post','http://54.227.7.149:8000/update_embedding',$body);

            HaiChatEmbedding::updateEmbedding($this->updateId, $this->updateEmbeddingName);

            session()->flash('embedding_success', "Embedding updated successfully.");

            $this->emit('closeEditEmbeddingModal');

//            $embeddingVector = GuzzleHelpers::createOpenAiEmbedding($this->embedding);

//            $filename = $embedding->request_id . ".txt";

//            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev/' : env("APP_ENV") . '/';

//            $path = $subFolder ? $subFolder . $filename : $filename;

//            Storage::disk('s3')->put($path, $this->updateEmbeddingText);

//            $embeddingPath = $subFolder . $embedding->request_id . '/' . $embedding->request_id . '.txt';
//
//            Storage::disk('s3')->put($embeddingPath, json_encode($embeddingVector));

//            $this->reset('updateEmbeddingName','updateEmbeddingText');

        }catch (\Illuminate\Validation\ValidationException $exception){

            session()->flash('embedding_errors', $exception->validator->errors()->getMessages());

        } catch (\Exception $exception) {

            session()->flash('embedding_error', $exception->getMessage());
        }

        $this->emit('closeAlert');

    }

    public function render()
    {
        $this->getEmbeddings();

        return view('livewire.admin.new-hai-chat.embedding');
    }
}
