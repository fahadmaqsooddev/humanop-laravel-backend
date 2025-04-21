<?php

namespace App\Http\Livewire\B2b\B2bPricingPlan;

use Livewire\Component;

class CreatePricingPlan extends Component
{

    public $plan_name,$price,$plan_type,$plan_desc;
    public function saveplan(){
        dd($this->plan_desc,$this->plan_name,$this->plan_type,$this->price);
    }
    public function render()
    {
        return view('livewire.b2b.b2b-pricing-plan.create-pricing-plan');
    }
}
