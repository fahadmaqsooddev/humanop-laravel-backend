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
        $intentionPlans = IntentionPlan::getIntentionPlan($this->userId);

        return view('livewire.client.setting.intention-plan-form', ['intentionPlans' => $intentionPlans['intentionOptions']]);
    }
}
