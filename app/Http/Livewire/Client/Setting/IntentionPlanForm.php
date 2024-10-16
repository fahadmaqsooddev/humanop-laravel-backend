<?php

namespace App\Http\Livewire\Client\Setting;

use Livewire\Component;
use App\Models\IntentionPlan\IntentionPlan;
use App\Helpers\Helpers;

class IntentionPlanForm extends Component
{
    public $userId;

    public function mount()
    {
        $user = Helpers::getWebUser();
        $intentionPlan = IntentionPlan::getIntentionPlan($user['id']);
        $this->intention = $intentionPlan ?? '';
        $this->userId = $user['id'];
    }

    public function submitForm()
    {
        try {

            IntentionPlan::updateIntentionPlan($this->userId, $this->intention);

            session()->flash('success', '90 Days Intention Plan updated successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function render()
    {
        return view('livewire.client.setting.intention-plan-form');
    }
}
