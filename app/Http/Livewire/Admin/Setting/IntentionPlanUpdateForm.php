<?php

namespace App\Http\Livewire\Admin\Setting;


use App\Models\IntentionPlan\IntentionOption;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class IntentionPlanUpdateForm extends Component
{
    public $description,$plan_id;

    protected $rules = [
        'description' => 'required',
    ];

    protected $messages = [
        'description.required' => 'Description is required',
    ];


    public function submitForm()
    {

        try {

            $validatedData = $this->validate();

            IntentionOption::updateIntentionPlan($validatedData,$this->plan_id);

            $this->emit('refreshIntentionPlanOption');

            session()->flash('success', 'Intention Plan updated successfully.');

        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.setting.intention-plan-update-form');
    }
}
