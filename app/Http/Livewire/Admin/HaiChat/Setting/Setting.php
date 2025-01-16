<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\Client\Plan\Plan;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\HaiChatSetting;
use Livewire\Component;

class Setting extends Component
{
    public $chatBot, $temperature, $max_token, $chunk, $model_type, $plans, $plan_id;

    public function submitForm()
    {
        try {

            $this->chatBot = Chatbot::updateChatBotSettings($this->chatBot->id, $this->temperature,$this->chunk, $this->max_token, $this->model_type, $this->plan_id);

            session()->flash('success', "Chat Bot Setting updated Successfully.");

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function render()
    {

        $this->plans = Plan::planNames();

        $this->temperature = $this->chatBot['temperature'] ?? 0.1;
        $this->max_token = $this->chatBot['max_tokens'] ?? 500;
        $this->chunk = $this->chatBot['chunks'] ?? 10;
        $this->model_type = $this->chatBot['model_type'] ?? 1;
        $this->plan_id = $this->chatBot['plan_id'] ?? null;

        return view('livewire.admin.hai-chat.setting.setting');
    }
}
