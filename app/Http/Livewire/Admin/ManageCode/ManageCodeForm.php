<?php

namespace App\Http\Livewire\Admin\ManageCode;

use Livewire\Component;
use App\Models\Admin\Code\CodeDetail;
use App\Traits\HandlesValidationErrors;
use App\Http\Requests\Admin\ManageCode\UpdateCodeRequest;

class ManageCodeForm extends Component
{
    use HandlesValidationErrors;
    public $select_code;

    public function mount($code)
    {
        $this->select_code = $code->toArray();

    }

    public function updateCode()
    {

        if($this->customValidation(new UpdateCodeRequest($this->select_code),$this->select_code)){return;};
        try {

            $keysToKeep = ['name', 'public_name', 'code', 'type', 'text'];
            $data = array_intersect_key($this->select_code, array_flip($keysToKeep));

            CodeDetail::updateCode($data, $this->select_code['id']);

          

            session()->flash('success', 'Manage Code updated successfully.');

        }catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    

    public function render()
    {
        return view('livewire.admin.manage-code.manage-code-form');
    }
}
