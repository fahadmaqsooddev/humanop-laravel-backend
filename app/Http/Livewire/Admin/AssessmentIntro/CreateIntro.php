<?php

namespace App\Http\Livewire\Admin\AssessmentIntro;

use Livewire\Component;
use App\Models\Admin\AssessmentIntro\AssessmentIntro;

class CreateIntro extends Component
{

    public $name, $public_name, $code, $type, $text, $number;

    protected $rules = [
        'name' => 'required|string|max:255',
        'public_name' => 'required|string|max:255',
        'code' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'text' => 'required|string',
        'number' => 'required|integer',
    ];

    public function createIntro()
    {
        try {
            $this->validate();
            $data = [
                'name' => $this->name,
                'public_name' => $this->public_name,
                'code' => $this->code,
                'type' => $this->type,
                'text' => $this->text,
                'number' => $this->number,
            ];

            AssessmentIntro::createIntro($data);

            $this->name = '';
            $this->public_name = '';
            $this->code = '';
            $this->type = '';
            $this->text = '';
            $this->number = '';

            session()->flash('success', ' Assessment Intro Created successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.admin.assessment-intro.create-intro');
    }
}
