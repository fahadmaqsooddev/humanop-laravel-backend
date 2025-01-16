<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatbotKeyword;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
class Prompt extends Component
{
    public $prompt, $restriction, $keyword = '', $keywords = [], $keyword_restriction_message, $chatBot;

    protected $rules = [
        'prompt' => 'required|max:5100',
        'restriction' => 'required|max:5100',
    ];

    protected $messages = [
        'prompt.required' => 'Prompt is required.',
        'restriction.required' => 'LLM Restriction is required.',
        'restriction.max' => 'LLM Restriction characters limit are 5000.',
        'prompt.max' => 'Prompt characters limit are 5000.',
    ];

    public function mount()
    {

        if($this->chatBot){

            $this->prompt = $this->chatBot['prompt'];

            $this->restriction = $this->chatBot['restriction'];
        }
    }
    public function update(){

        try {

            $this->validate();

            Chatbot::updatePrompts($this->chatBot->id, $this->prompt, $this->restriction);

            session()->flash('success', "Updated Successfully.");

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        } catch (\Exception $exception) {

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
                ],
                [
                    'keyword_restriction_message.required' => 'Keyword restriction message is required.',
                    'keyword_restriction_message.max' => 'Keyword restriction message character limit is 180.',
                    'keyword.required' => 'Keyword is required.',
                    'keyword.max' => 'Keyword character limit is 180.'
                ]);

            if ($this->keyword){

                ChatbotKeyword::createChatbotKeyword($this->keyword,$this->chatBot->id ?? null, $this->keyword_restriction_message);
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
        $this->keywords = ChatbotKeyword::chatbotKeywords($this->chatBot->id ?? null);

        return view('livewire.admin.hai-chat.setting.prompt');
    }
}
