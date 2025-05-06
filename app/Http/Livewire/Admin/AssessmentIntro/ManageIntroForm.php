<?php

namespace App\Http\Livewire\Admin\AssessmentIntro;

use Livewire\Component;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\AssessmentIntro\AssessmentIntro;
use App\Traits\HandlesValidationErrors;
use App\Http\Requests\Admin\ManageCode\UpdateCodeRequest;
class ManageIntroForm extends Component
{

    use HandlesValidationErrors;

    public $select_code;

    public function mount($assessment)
    {
        $this->select_code = $assessment->toArray();

    }

    public function updateIntro()
    {

        if($this->customValidation(new UpdateCodeRequest($this->select_code),$this->select_code)){return;};

        try {

            $keysToKeep = ['name', 'public_name', 'code', 'type', 'text'];

            $data = array_intersect_key($this->select_code, array_flip($keysToKeep));

            AssessmentIntro::updateIntro($data, $this->select_code['id']);
            

            session()->flash('success', 'Manage Assesment Intro updated successfully.');

        }catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    

    public function render()
    {
        return view('livewire.admin.assessment-intro.manage-intro-form');
    }
}
