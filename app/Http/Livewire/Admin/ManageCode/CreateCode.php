<?php

namespace App\Http\Livewire\Admin\ManageCode;

use Livewire\Component;
use App\Models\Admin\Code\CodeDetail;

class CreateCode extends Component
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

    public function createCode()
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

            CodeDetail::createCode($data);

            $this->resetForm();

            session()->flash('success', 'Manage Code Created successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function resetForm()
    {

        $this->reset(['name', 'public_name', 'code', 'type', 'text', 'number']);

    }

    public function render()
    {

        return view('livewire.admin.manage-code.create-code');

    }

}
