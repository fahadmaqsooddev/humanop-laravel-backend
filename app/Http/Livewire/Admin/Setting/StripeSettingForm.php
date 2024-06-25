<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\Admin\StripeSetting\StripeSetting;
use App\Http\Requests\Admin\Setting\StripeAccountSettingRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Traits\HandlesValidationErrors;
class StripeSettingForm extends Component
{
    use HandlesValidationErrors;
    public $account;


    public function mount($account)
    {
        $this->account = $account->toArray();
    }

    public function submitForm(){

        if($this->customValidation(new StripeAccountSettingRequest($this->account),$this->account)){return;};

        try
        {

            StripeSetting::updateStripeAccount($this->account, $this->account['id']);
             $this->emit('updateAmount');
            session()->flash('success', 'Stripe Account Update Successfully.');

        }catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.setting.stripe-setting-form');
    }
}
