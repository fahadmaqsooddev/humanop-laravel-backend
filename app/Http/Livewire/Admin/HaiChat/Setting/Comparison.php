<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Helpers\OpenRouterHelper;
use App\Models\Assessment;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use App\Models\HAIChai\LlmModel;

class Comparison extends Component
{
    public $bot_name;
    public $disliked = 0;
    public $user_id;
    public $modelTypes = [];
    public $model_value;
    public $selectedModels = [];
    public $selectedModel1;
    public $selectedModel2;
    public $val = 2;
    public $maxVal = 4;
    public $message;
    public $chatBots = [];
    public $chat_bot_id;

    public $modelResponse = [];

    protected $rules = [
        'message' => 'required|max:2000',
        'selectedModels' => 'required',
        'chat_bot_id' => 'required',
    ];

    protected $messages = [
        'selectedModels.required' => 'At least one model must be selected.',
        'message.required' => 'The Message field is required.',
        'message.max' => 'Query cannot contain more than 2000 characters.',
        'chat_bot_id' => 'Select chat-bot first.'
    ];

    public function addMore()
    {
        if ($this->val < $this->maxVal) {

            $this->val++;
        }
    }
    public function refreshComponent()
    {

      $this->model_value = "";
      $this->modelResponse = [];
       $this->val = 2;
       $this->selectedModels = [];
    }

    public function submitForm()
    {

        $this->modelResponse = [];

        $this->selectedModels = array_merge(
            (array) $this->selectedModel1,
            (array) $this->selectedModel2
        );

        $this->validate();

//        $chatBot = Chatbot::getChatFromVendorName($this->bot_name);

        $setting = HaiChatSetting::getHaiChatSetting($this->chat_bot_id);

        $activeChatAndEmbedding = HaiChatActiveEmbedding::getChatActiveEmbedding($this->bot_name);

        if ($this->user_id){

            $user_grid = Assessment::getAssessmentFromUserId($this->user_id);
        }

        if (!empty($this->selectedModels)) {

            foreach ($this->selectedModels as $llmModel) {

                $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->bot_name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked];

                $aiReply = $this->sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-model', $body);

                $openRouterResponse = OpenRouterHelper::callOpenRouterApi($this->message, $setting, $aiReply, $llmModel);

                if (!empty($openRouterResponse['choices'])) {

                    foreach ($openRouterResponse['choices'] as $choice) {

                        if (isset($choice['message']['content'])) {

                            $selectedModel = ['Deepseek' => 'deepseek/deepseek-chat', 'Qwen' => 'qwen/qvq-72b-preview', 'Deepseek R1-Qwen' => 'deepseek/deepseek-r1-distill-qwen-1.5b', 'OpenAI' => 'openai/gpt-3.5-turbo'];

                            $modelKey = array_search($openRouterResponse['model'], $selectedModel, true);

                            $this->modelResponse[] = [
                                'question' => $this->message,
                                'model' => $modelKey !== false ? $modelKey : $openRouterResponse['model'],
                                'response' => $choice['message']['content']
                            ];

                        }

                    }

                }

            }

        }

        $this->reset('message');

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
        $this->modelTypes = LlmModel::GetModels()->toArray();

        $this->chatBots = Chatbot::select(['id','name'])->get();

        return view('livewire.admin.hai-chat.setting.comparison');
    }
}
