<?php

namespace App\Http\Livewire\Admin\Setting;

use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Coupon\Coupon;
use App\Http\requests\Admin\Coupon\UpdateDiscountrequest;
use App\Traits\HandlesValidationErrors;
use Illuminate\Support\Str;
use Livewire\Component;

class DiscountSettingForm extends Component
{
    use HandlesValidationErrors;

    public $coupon, $account, $original_ammount;
    protected $listeners = ['updateAmount'];

    public function mount($coupon, $account)
    {
        $dis_amount = $account['amount'] - ($coupon['discount'] / 100 * $account['amount']);

        $dis_amount = (int)$dis_amount;
        $this->account = $dis_amount;
        $this->coupon = $coupon->toArray();
        $this->original_ammount = $account['amount'];
    }

    public function submitForm()
    {

        if ($this->customValidation(new UpdateDiscountRequest($this->coupon), $this->coupon)) {
            return;
        };

        try {

            $dis_amount = $this->original_ammount - ($this->coupon['discount'] / 100 * $this->original_ammount);

            $dis_amount = (int)$dis_amount;
            $this->account = $dis_amount;

            $this->coupon['coupon'] = preg_replace('/[^A-Za-z]/', '', Str::random(9));

            Coupon::updateDiscount($this->coupon, $this->coupon['id']);

            session()->flash('success', 'Discount Update Successfully.');

        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function updateAmount()
    {
        $this->render();
    }

    public function render()
    {
        return view('livewire.admin.setting.discount-setting-form');
    }
}
