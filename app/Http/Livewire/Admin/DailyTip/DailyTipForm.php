<?php

namespace App\Http\Livewire\Admin\DailyTip;

use Livewire\Component;
use App\Models\Admin\DailyTip\DailyTip;

class DailyTipForm extends Component
{

    public $code;
    public $title;
    public $description;

    protected $rules = [
        'code' => 'required',
        'title' => 'required',
        'description' => 'required',
    ];

    protected $messages = [
        'code.required' => 'Please select the code above.',
        'title.required' => 'Title is required',
        'description.required' => 'Description is required',
    ];

    public function selectCode($select_code)
    {
        $this->code = $select_code;

    }

    public function updateTip()
    {
        try {
            $validatedData = $this->validate();

            DailyTip::createTip($validatedData);
             $this->title = '';
             $this->description = '';
             $this->code = '';
            session()->flash('success', 'Daily Tip create successfully.');

        }catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function render()
    {
        return view('livewire.admin.daily-tip.daily-tip-form');
    }
}
