<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\Admin\DailyTip\DailyTip;
use Livewire\Component;

class DailyTipCreateForm extends Component
{
    public $title,$description,$code,$tip_id;
    protected $listeners = ['updateEditTipValues','emptyDailyTipValues','updateContent'];
    protected $rules = [
        'code' => 'required',
        'title' => 'required',
        'description' => 'required',
    ];

    protected $messages = [
        'code.required' => 'Code is required',
        'title.required' => 'Title is required',
        'description.required' => 'Description is required',
    ];

    public function selectCode($selectedCode){
        $this->code = $selectedCode;
    }


       public function updateContent($editorId, $data)
       {
        $this->description = $data;
       }

      public function updateEditTipValues($id,$code,$title,$description){

              $this->emptyDailyTipValues();
              $this->tip_id = $id;
              $this->code = $code;
              $this->title = $title;
              $this->description = $description;
              $this->emit('contentUpdated', $this->description);
    }

    public function emptyDailyTipValues(){
        $this->tip_id = '';
        $this->code = '';
        $this->title = '';
        $this->description = '';
    }

      public function updateTip(){
        try {

            $validatedData = $this->validate();

            if($this->tip_id){
                DailyTip::updateIntentionPlan($validatedData,$this->tip_id);
                $this->emit('closeModal');
                $this->reset();
                $this->emit('refreshDailyTips');
                $this->emit('updateSession','Updated');
            }else{
                DailyTip::createTip($validatedData);
                $this->emit('closeModal');
                $this->reset();
                $this->emit('refreshDailyTips');
                $this->emit('updateSession','Created');
            }
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {

        return view('livewire.admin.setting.daily-tip-create-form');
    }
}
