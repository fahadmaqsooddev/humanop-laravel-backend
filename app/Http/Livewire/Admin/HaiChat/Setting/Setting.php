<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\Client\Plan\Plan;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Setting extends Component
{
    public $chatSetting, $temperature, $max_token, $chunk, $model_type, $bot_name, $chat_bot_id, $plans, $plan_id, $is_published;
    public $modelTypes;
    public function getSetting()
    {
        $this->chatSetting = HaiChatSetting::getHaiChatSetting($this->chat_bot_id);
    }

    public function submitForm()
    {
        try {

            HaiChatSetting::updateHaiChatSetting($this->temperature, $this->max_token, $this->chunk, $this->model_type, $this->chat_bot_id, $this->plan_id);

            session()->flash('success', "Chatbot Setting updated Successfully.");

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function publishChatBot(){

        $this->submitForm();

        $active_embedding_ids = HaiChatActiveEmbedding::allRequestIds($this->bot_name);

        $model_value = LlmModel::singleModelFromValue($this->model_type);

//        $model_id = match((int)$this->model_type){
//            1 => 'gpt-4o-mini',
//            2 => 'gpt-4o',
//            3 => 'sonnet',
//            4 => 'ft:gpt-4o-mini-2024-07-18:personal::AdxDqOYu',
//        };

        $body = [
            'temperature' => $this->temperature,
            'max_tokens' => $this->max_token,
            'file_name' => $active_embedding_ids,
            'prompt_folder' => $this->bot_name,
            'total_chunks' => $this->chunk,
            'gpt_model' => $model_value['value'] ?? null,
        ];

        $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/save-llm-params', $body);

        Log::info(['log' => $aiReply]);

        if (isset($aiReply['s3_path'])){

            Chatbot::where('is_published', 1)->update(['is_published' => 0]);

            Chatbot::where('name', $this->bot_name)->update(['publish_path' => $aiReply['s3_path'], 'is_published' => 1]);
        }

        session()->flash('success', 'Chatbot published');
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
        $chatBot = Chatbot::getChatFromVendorName($this->bot_name);

        $getHaiSetting = HaiChatSetting::getHaiChatSetting($chatBot['id']);

        $model_value = LlmModel::singleModel($getHaiSetting['model_type']);

        $this->modelTypes= LlmModel::GetModels();
        // dd($this->modelTypes);
        $this->chat_bot_id = $chatBot->id ?? null;

        $this->is_published = $chatBot->is_published ?? 0;

        $this->getSetting();

        $this->plans = Plan::planNames();

        $this->temperature = $this->chatSetting['temperature'] ?? 0.1;
        $this->max_token = $this->chatSetting['max_token'] ?? 500;
        $this->chunk = $this->chatSetting['chunk'] ?? 10;
        $this->model_type = $model_value ? $model_value['model_value'] : null;
        $this->plan_id = $this->chatSetting['plan_id'] ?? null;

        return view('livewire.admin.hai-chat.setting.setting', ['chatSetting' => $this->chatSetting]);
    }
}
