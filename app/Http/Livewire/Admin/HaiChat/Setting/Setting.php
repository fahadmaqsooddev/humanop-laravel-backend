<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\Client\Plan\Plan;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\HaiChatSetting;
use Livewire\Component;

class Setting extends Component
{
    public $chatSetting, $temperature, $max_token, $chunk, $model_type, $bot_name, $chat_bot_id, $plans, $plan_id;

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

    public function render()
    {
        $this->chat_bot_id = Chatbot::getChatFromVendorName($this->bot_name)->id ?? null;

        $this->getSetting();

        $this->plans = Plan::planNames();

        $this->temperature = $this->chatSetting['temperature'] ?? 0.1;
        $this->max_token = $this->chatSetting['max_token'] ?? 500;
        $this->chunk = $this->chatSetting['chunk'] ?? 10;
        $this->model_type = $this->chatSetting['model_type'] ?? 1;
        $this->plan_id = $this->chatSetting['plan_id'] ?? null;

        return view('livewire.admin.hai-chat.setting.setting', ['chatSetting' => $this->chatSetting]);
    }
}
