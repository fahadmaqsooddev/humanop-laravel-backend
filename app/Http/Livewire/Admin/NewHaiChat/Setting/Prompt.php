<?php

namespace App\Http\Livewire\Admin\NewHaiChat\Setting;

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
    public $prompt,$restriction, $keyword = '', $keywords = [], $keyword_restriction_message, $chat_bot_id = null,
        $is_training = 0;

    protected $rules = [
        'chat_bot_id' => 'required',
        'prompt' => 'required|max:5100',
        'restriction' => 'required|max:5100',
    ];

    protected $messages = [
        'chat_bot_id.required' => 'Select chat-bot first',
        'prompt.required' => 'Prompt is required.',
        'restriction.required' => 'LLM Restriction is required.',
        'restriction.max' => 'LLM Restriction characters limit are 10000.',
        'prompt.max' => 'Prompt characters limit are 10000.',
    ];

    public $listeners = ['updateChatBotId','viewEditPersona'];

    public function updateChatBotId($value){

        $this->chat_bot_id = $value;

        if ($this->chat_bot_id){

            $persona = ChatPrompt::singlePersona($this->chat_bot_id);

            if($persona){

                $this->prompt = $persona['prompt'];
                $this->restriction = $persona['restriction'];
                $this->is_training = $persona['is_training'] ?? false;

            }

            $this->keywords = ChatbotKeyword::chatBotKeywordsFromId($this->chat_bot_id);
        }

    }

    public function viewEditPersona($id = null){

        $settings = ChatPrompt::whereId($id)->select(['chat_bot_id','id','is_training'])->first();

        $this->chat_bot_id = $settings->chat_bot_id ?? null;

        $this->is_training = $settings->is_training ?? false;
    }

    public function update(){

        try {

            $this->validate();

            ChatPrompt::createOrUpdatePersonaText($this->chat_bot_id, $this->prompt, $this->restriction, $this->is_training);

            session()->flash('success', "Updated Successfully.");

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        } catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }

        $this->emit('hideAlerts');
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
                    'chat_bot_id' => 'required'
                ],
                [
                    'keyword_restriction_message.required' => 'Keyword restriction message is required.',
                    'keyword_restriction_message.max' => 'Keyword restriction message character limit is 180.',
                    'keyword.required' => 'Keyword is required.',
                    'keyword.max' => 'Keyword character limit is 180.',
                    'chat_bot_id.required' => 'Select chat-bot first',
                ]);

            if ($this->keyword){

                ChatbotKeyword::createChatBotKeywordFromId($this->chat_bot_id, $this->keyword, $this->keyword_restriction_message);
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

    public function render()
    {

        if ($this->chat_bot_id){

            $persona = ChatPrompt::singlePersona($this->chat_bot_id);

            if($persona){

                $this->prompt = $persona['prompt'];

                $this->restriction = $persona['restriction'];

                $this->is_training = $persona['is_training'] ?? false;

            }

            $this->keywords = ChatbotKeyword::chatBotKeywordsFromId($this->chat_bot_id);

        }else{

            $this->reset('prompt','restriction','keyword','keywords','keyword_restriction_message','chat_bot_id');
        }

        return view('livewire.admin.new-hai-chat.setting.prompt');
    }
}
