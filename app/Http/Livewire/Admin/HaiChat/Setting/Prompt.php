<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatbotKeyword;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatSetting;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use GuzzleHttp\Client;
class Prompt extends Component
{
    public $prompt,$restriction, $keyword = '', $keywords = [], $keyword_restriction_message, $chat_bot_id = null, $name;

    protected $rules = [
        'name' => 'required',
        'prompt' => 'required|max:5100',
        'restriction' => 'required|max:5100',
    ];

    protected $messages = [
        'name.required' => 'Select chat-bot first',
        'prompt.required' => 'Prompt is required.',
        'restriction.required' => 'LLM Restriction is required.',
        'restriction.max' => 'LLM Restriction characters limit are 10000.',
        'prompt.max' => 'Prompt characters limit are 10000.',
    ];

    public $listeners = ['updateChatBotId','viewEditPersona'];

//    public function mount($name){
//
//        $this->chat_bot_id = Chatbot::where('name', $name)->first()->id ?? null;
//
//        $this->name = $name;
//    }

    public function updateChatBotId($value){

        $this->chat_bot_id = $value;

        if ($this->chat_bot_id){

            $chatBotName = Chatbot::whereId($this->chat_bot_id)->first()->name;

            if ($chatBotName){

                $this->name = $chatBotName;

                $detail = ChatPrompt::singlePromptByName($chatBotName);

                if($detail){

                    $this->prompt = $detail['prompt'];
                    $this->restriction = $detail['restriction'];

                }

                $this->keywords = ChatbotKeyword::chatbotKeywords($chatBotName);

            }

        }

    }

    public function viewEditPersona($id = null){

        $this->chat_bot_id = HaiChatSetting::whereId($id)->first()->chat_bot_id ?? null;
    }

    public function update(){

        try {

            $this->validate();

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

            $body = ['vendor_name' => $this->name,'base_data' => $this->prompt,'restriction_data' => $this->restriction, 'loc' => $subFolder];

            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'update-prompt', $body);

//            $aiReply = $this->sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/update-prompt', ['vendor_name' => $this->name,'base_data' => $this->prompt,'restriction_data' => $this->restriction, 'loc' => $subFolder]);

          if($aiReply > 0) {

              $prompt = ChatPrompt::createUpdatePrompt($this->name, $this->prompt, $this->restriction);

              if ($prompt) {
                  session()->flash('success', "Updated Successfully.");
              }

          }else{
              session()->flash('error', "Something went wrong.");
          }

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        } catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }

        $this->emit('hideAlerts');
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

    public function removeKeyword($id){

        ChatbotKeyword::removeChatbotKeyword($id);

    }

    public function createKeyword(){

        try {

            $this->validate(
                [
                    'keyword_restriction_message' => 'required|max:180',
                    'keyword' => 'required|max:180',
                    'name' => 'required'
                ],
                [
                    'keyword_restriction_message.required' => 'Keyword restriction message is required.',
                    'keyword_restriction_message.max' => 'Keyword restriction message character limit is 180.',
                    'keyword.required' => 'Keyword is required.',
                    'keyword.max' => 'Keyword character limit is 180.',
                    'name.required' => 'Select chat-bot first',
                ]);

            if ($this->keyword){

                ChatbotKeyword::createChatbotKeyword($this->keyword,$this->name, $this->keyword_restriction_message);
            }

            $this->keyword_restriction_message = "";

            $this->keyword = "";

        }catch (ValidationException $exception){

            session()->flash('keyword_restriction_errors', $exception->validator->errors()->getMessages());

            $this->emit('hideAlerts');
        }
        catch (\Exception $exception){

            session()->flash('keyword_restriction_error', $exception->getMessage());

            $this->emit('hideAlerts');
        }

    }

    public function updateKeywordRestrictionMessage(){

        try {

            $this->validate(
                ['keyword_restriction_message' => 'required|max:180','name' => 'required'],
                [
                    'keyword_restriction_message.required' => 'Keyword restriction message is required.',
                    'keyword_restriction_message.max' => 'Keyword restriction message character limit is 180.',
                    'name.required' => 'Select chat-bot first',
                ]);

            ChatPrompt::createUpdatePrompt($this->name,$this->prompt, $this->restriction, $this->keyword_restriction_message);

            session()->flash('keyword_restriction_success', 'Keyword restriction message updated');

            $this->emit('hideAlerts');

        }catch (ValidationException $exception){

            session()->flash('keyword_restriction_errors', $exception->validator->errors()->getMessages());

            $this->emit('hideAlerts');
        }
        catch (\Exception $exception){

            session()->flash('keyword_restriction_error', $exception->getMessage());

            $this->emit('hideAlerts');
        }

    }

    public function render()
    {

        if ($this->chat_bot_id){

            $chatBotName = Chatbot::whereId($this->chat_bot_id)->first()->name ?? null;

            if ($chatBotName){

                $this->name = $chatBotName;

                $detail = ChatPrompt::singlePromptByName($chatBotName);

                if($detail){

                    $this->prompt = $detail['prompt'];
                    $this->restriction = $detail['restriction'];

                }

                $this->keywords = ChatbotKeyword::chatbotKeywords($chatBotName);

            }

        }else{

            $this->reset('prompt','restriction','keyword','keywords','keyword_restriction_message','chat_bot_id');
        }

        return view('livewire.admin.hai-chat.setting.prompt');
    }
}
