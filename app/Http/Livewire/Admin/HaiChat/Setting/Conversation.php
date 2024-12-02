<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\HAIChai\Chatbot;
use App\Models\Assessment;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\User;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Conversation extends Component
{

    public $message, $name, $conversations,$user_details,$user_id;

    protected $rules = [
        'message' => 'required',
    ];

    protected $messages = [
        'message.required' => 'The Message field is required.',
    ];
    public function mount(){
        $user_ids = Assessment::getAllUser();
        $this->user_details = User::getUserDetailByIds($user_ids);
    }
    public function submitForm()
    {
        try {

            $this->validate();

            $chat_bot_id = Chatbot::getChatFromVendorName($this->name)->id ?? null;

            $setting = HaiChatSetting::getHaiChatSetting($chat_bot_id);

//            $chat_bot_id = Chatbot::getChatFromVendorName($this->name)->id ?? null;
//
//            $setting = HaiChatSetting::getHaiChatSetting($chat_bot_id ?? null);

            $activeChatAndEmbedding = HaiChatActiveEmbedding::getChatActiveEmbedding($this->name);

//            HaiChatConversation::createConversation($this->name, $this->message);

            if (HaiChatSetting::GPT_4o_MINI === $setting->model_type){

                $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'gpt-4o-mini'];

                $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/llm-gpt-model', $body);

            }elseif(HaiChatSetting::GPT_4o === $setting->model_type){

                $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'gpt-4o'];

                $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/llm-gpt-model', $body);

            }else{

                $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet'];

                $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/llm-model', $body);
            }
            HaiChatConversation::createConversation($this->name, $this->message,$aiReply['response']);
//            HaiChatConversation::updateConversation($this->name, $aiReply['response']);

            $this->message = '';

        } catch (\Exception $exception) {

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

    public function getChatBotConversation()
    {
        $this->conversations = HaiChatConversation::getConversation($this->name);
    }


    public function render()
    {
        $this->getChatBotConversation();
        return view('livewire.admin.hai-chat.setting.conversation', ['conversation' => $this->conversations]);
    }
}
