<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\HAIChai\HaiChatEmbedding;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class Embedding extends Component
{
    public $name,$embedding;
    use WithFileUploads;
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


    public function render()
    {
        return view('livewire.admin.hai-chat.setting.embedding');
    }
}
