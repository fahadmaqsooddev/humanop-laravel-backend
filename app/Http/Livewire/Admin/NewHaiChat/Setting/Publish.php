<?php

namespace App\Http\Livewire\Admin\NewHaiChat\Setting;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatPublish;
use App\Models\HAIChai\HaiChatSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Publish extends Component
{

    public $bot_name;
    public $chatBot;
    public $buttonText = 'Publish';

    public function mount()
    {
        $this->buttonText = 'Publish';
         $this->chatBot = Chatbot::getChatFromName($this->bot_name);
    }

    public function publishForm()
    {
        try {

            $chatBotId = Chatbot::getChatFromName($this->bot_name);

            $chatSetting = HaiChatSetting::getHaiChatSetting($chatBotId['id']);

            if (!empty($chatBotId) && !empty($chatSetting))
            {

                $activeEmbedings = HaiChatActiveEmbedding::allActiveEmbeddings($this->bot_name);

                $file_name = [];

                foreach ($activeEmbedings as $embedding)
                {
                    $file_name []= $embedding['request_id'];
                }

                $gpt_model = match ($chatSetting['model_type']) {1 => 'gpt-4o-mini', 2 => 'gpt-4o', 3 => 'sonnet', 4 => 'ft:gpt-4o-mini-2024-07-18:personal::AdxDqOYu',};

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

                $body = ['temperature' => $chatSetting['temperature'],'max_tokens' => $chatSetting['max_token'],'file_name' => $file_name, 'prompt_folder' => $this->bot_name, 'total_chunks' => $chatSetting['chunk'], 'gpt_model' => $gpt_model, 'loc' => $subFolder];

                $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'save-llm-params', $body);

//                $aiReply = $this->sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/save-llm-params', ['temperature' => $chatSetting['temperature'],'max_tokens' => $chatSetting['max_token'],'file_name' => $file_name, 'prompt_folder' => $this->bot_name, 'total_chunks' => $chatSetting['chunk'], 'gpt_model' => $gpt_model, 'loc' => $subFolder]);

                if ($aiReply['status'] == 'success')
                {

                    Chatbot::where('name', $this->bot_name)->update(['publish' => $aiReply['s3_path']]);

                    $this->buttonText = 'Published';

                    $this->emit('changeButtonText');

                    session()->flash('success', $aiReply['message']);

                }
                else
                {
                    session()->flash('error', $aiReply['message']);

                }
            }


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


    public function render()
    {

        return view('livewire.admin.new-hai-chat.setting.publish');
    }
}
