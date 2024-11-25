<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\HAIChai\HaiChatSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Conversation extends Component
{

    public $message, $name, $conversations;

    protected $rules = [
        'message' => 'required',
    ];

    protected $messages = [
        'message.required' => 'The Message field is required.',
    ];

    public function submitForm()
    {
        try {

            $this->validate();

            $setting = HaiChatSetting::getHaiChatSetting();

            $activeChatAndEmbedding = HaiChatActiveEmbedding::getChatActiveEmbedding($this->name);

            HaiChatConversation::createConversation($this->name, $this->message);

            if (HaiChatSetting::GPT_4o_MINI === $setting->model_type){

                $body = ['query' => $this->message, 'temperature' => 0.3, 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'gpt-4o-mini'];

            }elseif(HaiChatSetting::GPT_4o === $setting->model_type){

                $body = ['query' => $this->message, 'temperature' => 0.3, 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'gpt-4o'];

            }else{

                $body = ['query' => $this->message, 'temperature' => 0.3, 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk']];
            }

            $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/llm-model', $body);

            HaiChatConversation::updateConversation($this->name, $aiReply['response']);

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
