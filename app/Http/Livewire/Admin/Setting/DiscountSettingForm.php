<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\Admin\Coupon\Coupon;
use Livewire\Component;

class DiscountSettingForm extends Component
{
    public $discount;
    public $limit;

    protected $rules = [
        'discount' => 'required',
        'limit' => 'required',
    ];

    protected $messages = [
        'discount.required' => 'Discount is required',
        'limit.required' => 'Discount limit is required',
    ];


    public function submitForm()
    {

        try {

            $validatedData = $this->validate();

            Coupon::createDiscount($validatedData);

            $this->discount = '';
            $this->limit = '';

            session()->flash('success', 'Coupon create successfully.');

        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.setting.discount-setting-form');
    }
}
