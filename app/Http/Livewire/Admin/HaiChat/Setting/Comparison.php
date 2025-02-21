<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use Livewire\Component;
use App\Models\HAIChai\LlmModel;
use App\Models\HAIChai\AnalyticsModel;
use App\Models\User;

class Comparison extends Component
{

    public $modelTypes=[];
    public $model_value;

   public $user_details;

   public $user_id;
    public $selectedModels = [];
    public $val = 2;
    public $maxVal = 4;

    public function addMore()
    {
        if ($this->val < $this->maxVal) {
            $this->val++; // Increase the number of models displayed
        }
    }
   

    public function render()
    {
        $this->modelTypes= LlmModel::GetModels()->toArray();
        $this->user_details = User::getUserDetailByIds();
    
        return view('livewire.admin.hai-chat.setting.comparison');
    }
}
