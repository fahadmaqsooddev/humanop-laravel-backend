<?php

namespace App\Http\Livewire\Admin\InformationIcon;

use App\Models\Information\InformationIcon;
use Livewire\Component;

class InformationList extends Component
{

    public $name, $information;

    protected $rules = [
        'name' => 'required',
        'information' => 'required|max:2000',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'information.required' => 'Information is required',
    ];

    public function submitForm()
    {

        try {

            $this->validate();

            InformationIcon::createInfo($this->name, $this->information);

            session()->flash('success', "{$this->name} information created successfully.");

            $this->resetForm();

        }catch (\Exception $exception)
        {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'information']);
    }

    public function editSubmitForm()
    {

        try {

            $this->validate(['name' => 'required', 'information' => 'required|max:2000']);

            InformationIcon::editInfo($this->name, $this->information);

            session()->flash('success', "{$this->name} information updated successfully.");

            $this->resetForm();

        }catch (\Exception $exception)
        {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function render()
    {
        $iconInformations = InformationIcon::getInfo();

        return view('livewire.admin.information-icon.information-list', ['iconInformations' => $iconInformations]);
    }
}
