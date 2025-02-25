<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use Livewire\Component;
use App\Models\HAIChai\LlmModel;
use App\Models\HAIChai\AnalyticsModel;

class Analytics extends Component
{

    public $modelTypes;
    public $model_value;
    public $data=[];

    public function updatedModelValue($value)
    {
      $this->data= AnalyticsModel::getData($this->model_value);
      
    }
    public function render()
    {
        $this->modelTypes= LlmModel::GetModels();
       
        return view('livewire.admin.hai-chat.setting.analytics');
    }
}
