<?php

namespace App\Http\Livewire\Client\Chat;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use App\Helpers\Assessments\AssessmentHelper;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;

class Index extends Component
{
    public $userMessage = '';
    public $messages = [];
    public $likeActive = false;
    public $dislikeActive = false;
    public $dislikeClickedOnce = false;

    protected $listeners = ['chatMessage'];

    public function like()
    {
        $this->likeActive = true;
        $this->dislikeActive = false;
        $this->dislikeClickedOnce = false; // Reset dislike click counter
    }

    public function dislike()
    {
            $this->likeActive = false;
            $this->dislikeActive = true;
    }

    public function showModal()
    {

    }

    public function chatMessage($message)
    {
        $this->userMessage = $message;
    }

    public function sendMessage(){

       if(isset($this->userMessage)){

           if ($this->userMessage !== '')
           {

               $assessments = AssessmentHelper::getAssessments();
               $assessmentDetails = Assessment::getAssessment();
               $this->messages[] = ['type' => 'user', 'text' => $this->userMessage];

               $aiReply = $this->sendRequestFromGuzzle('post','http://44.201.128.253:8000/llm-data',['question' => $this->userMessage, 'user_id' => auth()->user()->id, 'assessment_ids' => $assessments, 'assessment_details' => $assessmentDetails]);

               $this->messages[] = ['type' => 'bot', 'text' => $aiReply];
               $this->emit('updateAiMessage');
               $this->userMessage = '';
           }
       }
    }

    public function sendRequestFromGuzzle($method = null, $route_name = null, $body = []) {
        $authorization = Request::header('Authorization');
        $queryArray = [
            'headers' => ['Authorization' => $authorization],
            'json' => $body
        ];
        $client = new Client(['http_errors' => false, 'timeout' => 180]);
        $route = $route_name;
        $response = $client->request($method, $route, $queryArray);
//        $statusCode = $response->getStatusCode();
        $response_body = json_decode($response->getBody()->getContents(), true);
        return $response_body;
    }

    public function render() {
        return view('livewire.client.chat.index', ['messages' => $this->messages]);
    }
}
