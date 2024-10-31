<?php

namespace App\Http\Livewire\Client\Setting;

use App\Models\IntentionPlan\IntentionOption;
use Livewire\Component;
use App\Models\IntentionPlan\IntentionPlan;
use App\Helpers\Helpers;

class IntentionPlanForm extends Component
{
    public $userId, $selectedIntention = [];

    public function mount()
    {
        $user = Helpers::getWebUser();
        $this->selectedIntention = IntentionPlan::getIntentionPlan($user['id'])->pluck('intention_option_id')->toArray();
        $this->userId = $user['id'];
    }

    public function submitForm()
    {
        try {
            IntentionPlan::updateIntentionPlan($this->userId, $this->selectedIntention);
            session()->flash('success', '90 Days Intention Plan updated successfully.');
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        $intentionOptions = IntentionOption::getOptions();
        return view('livewire.client.setting.intention-plan-form', ['intentionOptions' => $intentionOptions]);
    }
}

