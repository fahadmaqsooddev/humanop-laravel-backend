<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\IntentionPlan\IntentionOption;
use Livewire\Component;
use App\Models\Admin\Coupon\Coupon;

class IntentionPlanCreateForm extends Component
{
    public $description;

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

            IntentionOption::createPlanOption($validatedData);

            $this->reset();
            $this->emit('refreshIntentionPlanOption');

            session()->flash('success', 'Intention Plan created successfully.');

        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.setting.intention-plan-create-form');
    }
}
