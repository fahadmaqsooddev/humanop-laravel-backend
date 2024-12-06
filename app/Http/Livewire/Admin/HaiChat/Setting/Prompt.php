<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

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
    public $prompt,$restriction, $keyword = '', $keywords = [], $keyword_restriction_message;
    public $name;
    protected $rules = [
        'name' => 'required',
        'prompt' => 'required|max:2000',
        'restriction' => 'required|max:2000',
    ];

    protected $messages = [
        'name.required' => 'Something went during updating prompt.',
        'prompt.required' => 'Prompt is required.',
        'restriction.required' => 'LLM Restriction is required.',
        'restriction.max' => 'LLM Restriction maximum characters are 2000.',
    ];

    public function mount($name)
    {
        $this->name = $name;
        $detail= ChatPrompt::singlePromptByName($this->name);
        if($detail){
            $this->prompt = $detail['prompt'];
            $this->restriction = $detail['restriction'];
            $this->keyword_restriction_message = $detail['keyword_restriction_message'];
        }
    }
    public function update(){
        try {
            $this->validate();
            $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/update-prompt', ['vendor_name' => $this->name,'base_data' => $this->prompt,'restriction_data' => $this->restriction]);
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

        if ($this->keyword){

            ChatbotKeyword::createChatbotKeyword($this->keyword,$this->name);
        }

        $this->keyword = "";

    }

    public function updateKeywordRestrictionMessage(){

        $this->validate(
            ['keyword_restriction_message' => 'required|max:180'],
            [
                'keyword_restriction_message.required' => 'Keyword restriction message is required.',
                'keyword_restriction_message.max' => 'Keyword restriction message character limit is 180.'
            ]);

        ChatPrompt::createUpdatePrompt($this->name,$this->prompt, $this->restriction, $this->keyword_restriction_message);

        session()->flash('success', 'Keyword restriction message updated');

    }

    public function render()
    {

        $this->keywords = ChatbotKeyword::chatbotKeywords($this->name);

        return view('livewire.admin.hai-chat.setting.prompt');
    }
}
