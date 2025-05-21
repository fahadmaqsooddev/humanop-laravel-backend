<?php

namespace App\Http\Livewire\Admin\NewHaiChat;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\LearningCluster\LearningClusterHelpers;
use App\Models\HAIChai\BrainCluster;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class HaiChat extends Component
{
    public $chats, $name, $description, $chatBot, $copyChatBotId, $search_brain;
    protected $listeners = ['deleteChatbot'];
    protected $rules = [
        'name' => 'required|max:30|unique:chatbot,brain_name,NULL,id,deleted_at,NULL',
        'description' => 'required|max:2000',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'description.required' => 'Information is required',
    ];

    public function updatedSearchBrain($value){

        $this->search_brain = $value;
    }

    public function submitForm()
    {

        try {

            $this->validate();

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

            $body = ['vendor_n' => $this->name, 'loc' => $subFolder];

            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'create-chatbot', $body);

//            $aiReply = $this->sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/create-chatbot', ['vendor_n' => $this->name, 'loc' => $subFolder]);

            $chatbot = Chatbot::createChatBot($aiReply, $this->description);

            HaiChatSetting::updateHaiChatSetting(null,null,null,null,$chatbot->id);

            session()->flash('success', "Chatbot created successfully.");

            $this->resetForm();

            $this->emit('closeModel');

            $this->emit('closeAlert');

        }catch (ValidationException $exception) {

            session()->flash('errors', $exception->validator->errors()->getMessages());

            $this->emit('closeAlert');

        }catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

            $this->emit('closeAlert');

        }
    }

    public function deleteChatbot($id)
    {
        $chat = Chatbot::singleChat($id);

        $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

        $body = ['folder_n' => $chat['name'], 'loc' => $subFolder];

        $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'delete-folder', $body);

//        $aiReply = $this->sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/delete-folder', ['folder_n' => $chat['name'], 'loc' => $subFolder]);

        if ($aiReply == 1)
        {

            Chatbot::deleteChat($id);

            LearningClusterHelpers::deleteLearningClusterFile($chat->brain_name);

            session()->flash('success', "Chatbot deleted successfully.");

            $this->emit('closeAlert');

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

    public function resetForm()
    {
        $this->reset(['name', 'description']);
    }

    public function getChats()
    {
        $this->chats = Chatbot::allChats($this->search_brain);
    }

    public function showModalChatBotDetail($id){

        $this->chatBot = Chatbot::singleChat($id);
    }

    public function closeChatBotDetailModal(){

        $this->chatBot = null;
    }

    public function copyChatBot($id){

        $this->reset('name','description');

        $this->copyChatBotId = $id;
    }

    public function createDuplicateChatBot(){

        try {

            $this->validate();

            $chatBot = Chatbot::singleChat($this->copyChatBotId);

            if ($chatBot){

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

                $body = ['vendor_n' => $this->name, 'loc' => $subFolder];

                $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'create-chatbot', $body);

//                $aiReply = $this->sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/create-chatbot', ['vendor_n' => $this->name, 'loc' => $subFolder]);

                $newChatBot = Chatbot::createChatBot($aiReply, $this->description ?? $chatBot->description, $this->name);

                ChatPrompt::duplicatingChatBot($chatBot->name, $aiReply);

                HaiChatSetting::duplicatingChatBotSetting($chatBot->id, $newChatBot->id);

                BrainCluster::addDuplicateBrainClusters($chatBot->id,$newChatBot->id);

                $this->reset('copyChatBotId','name','description');

                $this->emit('closeCopyChatBot');

            }

            session()->flash('success','Chatbot copied');

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

//    public function publishChatBot($chat_bot_id){
//
//        $chatBot = Chatbot::whereId($chat_bot_id)->first();
//
//        if ($chatBot){
//
//            $settings = HaiChatSetting::where('chat_bot_id', $chat_bot_id)->first();
//
//            if(!$settings){
//
//                HaiChatSetting::updateHaiChatSetting(0.5, 500, 1, null, $chat_bot_id);
//
//                $settings = HaiChatSetting::where('chat_bot_id', $chat_bot_id)->first();
//            }
//
//            $active_embedding_ids = HaiChatActiveEmbedding::allRequestIds($chatBot->name);
//
//            $model_value = LlmModel::singleModelFromValue($settings['model_type']);
//
//            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");
//
//            $body = [
//                'temperature' => $settings['temperature'],
//                'max_tokens' => $settings['max_token'],
//                'file_name' => $active_embedding_ids,
//                'prompt_folder' => $chatBot['name'],
//                'total_chunks' => $settings['chunk'],
//                'gpt_model' => $model_value['model_value'] ?? null,
//                'loc' => $subFolder
//            ];
//
//            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'save-llm-params', $body);
//
//            if (isset($aiReply['s3_path'])){
//
//                Chatbot::where('is_published', 1)->update(['is_published' => 0]);
//
//                Chatbot::where('name', $chatBot['name'])->update(['publish_path' => $aiReply['s3_path'], 'is_published' => 1]);
//            }
//
//        }
//
//
//    }

    public function redirectToCreateBrainInterface(){

        try {

            $this->validate();

            return redirect()->route('admin_create_brain')->with(['name' => $this->name, 'description' => $this->description]);

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function render()
    {
        $this->getChats();

        return view('livewire.admin.new-hai-chat.hai-chat', ['chats' => $this->chats]);
    }
}
