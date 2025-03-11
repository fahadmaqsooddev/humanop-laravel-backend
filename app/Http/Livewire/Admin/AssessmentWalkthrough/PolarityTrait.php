<?php

namespace App\Http\Livewire\Admin\AssessmentWalkthrough;
use Livewire\Component;
use App\Enums\Admin\Admin;
use App\Models\Admin\AssessmentWalkthrough\AssessmentWalkThrough;

class PolarityTrait extends Component
{
    public $code = [];
    // public $heading='polarity';
    public $overview;
    public $optimal;
    public $optimization;

    protected $rules = [
        'code' => 'required',
        'overview' => 'required',
        'optimal' => 'required',
        'optimization' => 'required',
    ];

    protected $messages = [
        'code.required' => 'please selected code ',
        'overview.required' => 'overview is required.',
        'optimal.required' => 'optimal is required.',
        'optimization.required' => 'optimization is required.',
       
    ];


    public function selectCode($selectedCode)
    {
       
        $this->code = [];
        $this->code[] = $selectedCode;
        $result=AssessmentWalkThrough::getData(Admin::POLARITY_TRAIT,$this->code);
        
        $this->overview=$result->overview??"";
        $this->optimal=$result->optimal??"";
        $this->optimization=$result->optimization??'';
            
    }

    public function update(){
        try {
            $this->validate();
            $result=AssessmentWalkThrough::storeData($this->overview,$this->code,$this->optimal,$this->optimization,Admin::POLARITY_TRAIT);
            
            if ($result) {
                session()->flash('success', 'Data has been saved successfully.');
            } else {
                session()->flash('error', 'Failed to save data.');
            }
            $this->resetForm(); 

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }
    }

    public function resetForm()
    {
        $this->code = [];
        $this->overview = '';
        $this->optimal = '';
        $this->optimization = '';
    }

    public function render()
    {
        return view('livewire.admin.assessment-walkthrough.polarity-trait');
    }
}
