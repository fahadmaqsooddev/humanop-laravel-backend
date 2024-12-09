<?php

namespace App\Http\Livewire\Client\Setting;

use App\Models\IntentionPlan\IntentionOption;
use Livewire\Component;
use App\Models\IntentionPlan\IntentionPlan;
use App\Helpers\Helpers;

class IntentionPlanForm extends Component
{
    public $userId, $selectedIntention = [];

    protected $rules = [
        'selectedIntention' => 'required|array|min:1',
    ];

    protected $messages = [
        'selectedIntention.required' => 'Please select at least one intention.',
        'selectedIntention.min' => 'You must select at least one intention.',
    ];

    public function mount()
    {
        $user = Helpers::getWebUser();

        $this->selectedIntention = IntentionPlan::getIntentionPlan($user['id'])->pluck('intention_option_id')->toArray();

        $this->userId = $user['id'];
    }

    public function submitForm()
    {
        try {

            $this->validate();

            IntentionPlan::updateIntentionPlan($this->userId, $this->selectedIntention);

            session()->flash('success', 'updated successfully.');

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

