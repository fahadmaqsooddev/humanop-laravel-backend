<?php

namespace App\Http\Livewire\Admin\HaiChat\Knowledge;

use App\Helpers\LearningCluster\LearningClusterHelpers;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use App\Models\HAIChai\TrainingFile;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewEmbedding extends Component
{

    use WithFileUploads;

    public $file_text, $allFiles = [], $edit_file_id, $edited_name = null, $uploadedFile = null;

    protected $rules = [
        'file_text' => 'required_without:uploadedFile',
        'uploadedFile' => 'nullable|mimes:txt',
    ];

    protected $messages = [
        'file_text.required' => 'Text is required',
    ];

    public function render()
    {

        $this->allFiles = TrainingFile::all();

        return view('livewire.admin.hai-chat.knowledge.new-embedding');
    }

    public function addToTrainingQueue(){

        try {

            $this->validate();

            if ($this->uploadedFile){

                $filePath = $this->uploadedFile->getRealPath();

                $this->file_text = file_get_contents($filePath);
            }

            $file_name = Str::random(10);

            TrainingFile::createFile($file_name);

            Storage::disk('local')->put("training_files/" . $file_name . '.txt', $this->file_text);

            $this->reset('file_text');

            session()->flash('success', 'File added to training queue.');

        }catch (ValidationException $validationException){

            session()->flash('errors', $validationException->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('error', $exception->getMessage());
        }
    }

    public function deleteFileFromTrainingQueue($file_id){

        $file = TrainingFile::whereId($file_id)->first();

        if ($file){

            Storage::disk('local')->delete("training_files/" . $file->file_name);

            $file->delete();

        }

        session()->flash('success', 'File removed.');
    }

    public function editFileName($file_id){

        $this->edited_name = TrainingFile::whereId($file_id)->first()->name ?? null;

        if ($this->edited_name){

            $this->edit_file_id = $file_id;
        }

    }

    public function updateFileName(){

        try {

            $this->validate(['edited_name' => 'required|max:20']);

            TrainingFile::updateFileName($this->edit_file_id, $this->edited_name);

            $this->reset('edit_file_id','edited_name');

        }catch (ValidationException $validationException){

            session()->flash('errors', $validationException->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('error', $exception->getMessage());
        }

    }

    public function startTraining(){

        try {

            $files = TrainingFile::all();

            foreach ($files as $file){

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

                $multipart = [
                    [
                        'name'     => 'file', // Field name expected by the server
                        'contents' => Storage::disk('local')->get('training_files/' . $file->file_name),
                        'filename' => $file->file_name,
                    ],
                    [
                        'name' => "loc",
                        'contents' => $subFolder
                    ]
                ];

                $aiReply = $this->sendCreateRequestFromGuzzle('POST', 'http://54.227.7.149:8000/upload_embedding', [
                    'multipart' => $multipart
                ]);

//                $aiReply = $this->sendCreateRequestFromGuzzle('POST', 'http://54.227.7.149:8000/upload/pineconeapi/', [
//                    'multipart' => $multipart
//                ]);

                if(isset($aiReply['request_id'])){

                    $embedding = HaiChatEmbedding::createEmbedding($file->name,$aiReply['request_id']);

                    $this->emit('$refresh');

                    if($embedding){

                        Storage::disk('local')->delete('training_files/'. $file->file_name);

                        $file->delete();

                    }

                }

//                if(isset($aiReply['results'][0])){
//
//                    if (!empty($aiReply['results'][0]['file_uuid'])){
//
//                        $embedding = HaiChatEmbedding::createEmbedding($file->name,$aiReply['results'][0]['file_uuid']);
//
//                        $this->emit('$refresh');
//
//                        if($embedding){
//
//                            Storage::disk('local')->delete('training_files/'. $file->file_name);
//
//                            $file->delete();
//
//                        }
//
//                    }
//                }

            }

            session()->flash('embedding_success', "Embedding created successfully.");

        }catch (ValidationException $validationException){

            session()->flash('errors', $validationException->validator->errors()->getMessages());

        }catch (\Exception $exception){

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
            'multipart' => $body['multipart'],
        ];

        // Initialize Guzzle client
        $client = new Client(['http_errors' => false, 'timeout' => 180]);

        // Send the request
        $response = $client->request($method, $route_name, $queryArray);

        // Get and decode the response body
        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }
}
