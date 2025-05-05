<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\OpenRouterHelper;
use App\Models\Assessment;
use App\Models\HAIChai\BrainCluster;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\HAIChai\HaiChatSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
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
        'chat_bot_id' => 'required',
        'selectedModel1' => 'required',
        'selectedModel2' => 'required',
    ];

    protected $messages = [
        'selectedModel1.required' => 'Select model to compare to.',
        'selectedModel2.required' => 'Select model to compare with.',
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

        try {

            $this->modelResponse = [];

            $this->selectedModels = array_merge(
                (array) $this->selectedModel1,
                (array) $this->selectedModel2
            );

            $this->validate();

            $setting = HaiChatSetting::getHaiChatSetting($this->chat_bot_id);

            $chatbot = Chatbot::whereId($this->chat_bot_id)->first();

            $activeChatAndEmbedding = BrainCluster::connectedClusterEmbeddingIds($this->chat_bot_id);

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

            $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $chatbot->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked, 'loc' => $subFolder, 'user_name' => "null", 'user_id' => 0];

            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'llm-model', $body);

            Log::info(['ai ' => $aiReply]);

            $prompts = ChatPrompt::where('name',$chatbot['name'])->first();

            $llm_prompt = OpenRouterHelper::addUserDetailsIntoPrompt(null, $aiReply['prompt']);

            $final_persona = OpenRouterHelper::createFinalPersona($prompts['prompt'] ?? "");

            [$userMessage, $assistantMessage] = HaiChatConversation::userLastMessage($chatbot['name'],null);

            foreach ($this->selectedModels as $llmModel) {

                $openRouterResponse = OpenRouterHelper::callOpenRouterApi($this->message, $setting, $llm_prompt, $llmModel, $final_persona, $userMessage,$assistantMessage);

                Log::info(['open router' => $openRouterResponse]);

                foreach ($openRouterResponse['choices'] as $choice)
                {

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

            $this->reset('message');

        }catch (\Exception $exception){

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

    public function render()
    {
        $this->modelTypes = LlmModel::GetModels()->toArray();

        $this->chatBots = Chatbot::select(['id','name'])->get();

        return view('livewire.admin.hai-chat.setting.comparison');
    }
}
