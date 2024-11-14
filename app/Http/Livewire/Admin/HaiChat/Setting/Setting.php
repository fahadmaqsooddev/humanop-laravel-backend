<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\HAIChai\HaiChatSetting;
use Livewire\Component;

class Setting extends Component
{
    public $chatSetting, $temperature, $max_token, $chunk;

    public function getSetting()
    {
        $this->chatSetting = HaiChatSetting::getHaiChatSetting();
    }

    public function submitForm()
    {
        try {

            HaiChatSetting::updateHaiChatSetting($this->temperature, $this->max_token, $this->chunk);

            session()->flash('success', "Chatbot Setting updated Successfully.");

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function render()
    {
        $this->getSetting();

        $this->temperature = $this->chatSetting['temperature'] ?? 0.1;
        $this->max_token = $this->chatSetting['max_token'] ?? 500;
        $this->chunk = $this->chatSetting['chunk'] ?? 10;

        return view('livewire.admin.hai-chat.setting.setting', ['chatSetting' => $this->chatSetting]);
    }
}
