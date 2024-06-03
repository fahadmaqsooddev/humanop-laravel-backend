<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\Admin\StripeSetting\StripeSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StripeSettingForm extends Component
{
    public $account;


    public function mount($account)
    {
        $this->account = $account->toArray();
    }
    protected $rules = [
        'account.account_name' => 'required',
        'account.account_email' => 'required|email',
        'account.api_key' => 'required',
        'account.public_key' => 'required'
    ];

    protected function validationAttributes()
    {
        return [
            'account.account_name' => 'account name',
            'account.account_email' => 'account email',
            'account.api_key' => 'api key',
            'account.public_key' => 'public key',
        ];
    }

    public function submitForm(){
        $this->validate();
        try
        {
            StripeSetting::updateStripeAccount($this->account, Auth::user()->id);

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
