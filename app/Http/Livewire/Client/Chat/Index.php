<?php

namespace App\Http\Livewire\Client\Chat;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use App\Helpers\Assessments\AssessmentHelper;
use App\Models\Assessment;
use App\Models\HAIChai\HaiChat;

class Index extends Component
{
    public $userMessage = '';
    public $lastMessage;

    protected $listeners = ['chatMessage'];

    public function like($id)
    {
        $chat = HaiChat::getSingleChat($id);
        HaiChat::updateChat($chat['id'], 2);
    }

    public function dislike($chatId)
    {
        $chat = HaiChat::getSingleChat($chatId);

        if ($chat['likedislike'] == 3 || $chat['likedislike'] == 2) {

            HaiChat::updateChat($chat['id'], 1);

            $this->userMessage = $this->lastMessage;
            $this->sendMessage(1);

        } else {

            HaiChat::updateChat($chat['id'], 0);
            $this->emit('showUserAnswerModal');
        }
    }

    public function chatMessage($message)
    {
        $this->userMessage = $message;
    }

    public function sendMessage($is_repeat_answer = 0)
    {

        if (isset($this->userMessage)) {

            if ($this->userMessage !== '') {

                $assessments = AssessmentHelper::getAssessments();
                $assessmentDetails = Assessment::getAssessment();

                $aiReply = $this->sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-data', ['question' => $this->userMessage, 'user_id' => auth()->user()->id, 'assessment_ids' => $assessments, 'assessment_details' => $assessmentDetails, 'is_repeat' => $is_repeat_answer]);

                HaiChat::createChat($this->userMessage, $aiReply);
                $this->emit('updateAiMessage');
                $this->lastMessage = $this->userMessage;
                $this->userMessage = '';
            }
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

    public function render()
    {

        $chats = HaiChat::getChat();
        return view('livewire.client.chat.index', ['messages' => $chats]);
    }
}
