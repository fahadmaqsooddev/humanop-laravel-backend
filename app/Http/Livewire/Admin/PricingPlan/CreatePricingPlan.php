<?php

namespace App\Http\Livewire\Admin\PricingPlan;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Client\Plan\Plan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class CreatePricingPlan extends Component
{
    public $plan_name, $price, $plan_type, $plans, $plan_id, $plan_detail;

    protected $rules = [
        'plan_name' => 'required',
        'price' => 'required',
        'plan_type' => 'required',
    ];

    protected $messages = [
        'plan_name.required' => 'Plan name is required.',
        'price.required' => 'Price is required.',
        'plan_type.required' => 'Plan type is required.',
    ];

    public function submitForm()
    {
        DB::beginTransaction();

        try {

            $this->validate();

            Stripe::setApiKey(config('cashier.secret'));

            $product = Product::create([
                'name' => $this->plan_name,
            ]);

            $price = Price::create([
                'unit_amount' => $this->price * 100,
                'currency' => 'usd',
                'recurring' => ['interval' => $this->plan_type],
                'product' => $product->id,
            ]);

            $this->data = [
                'plan_id' => $price['id'],
                'name' => $product['name'],
                'billing_method' => $price['recurring']['interval'],
                'interval_count' => $price['recurring']['interval_count'],
                'price' => $this->price,
                'currency' => $price['currency'],
                'plan_type' => Admin::B2C_PLAN,
                'plan_detail' => $this->plan_detail,
            ];

            Plan::storePlan($this->data);

            DB::commit();

            $this->emit('refreshPage');

            session()->flash('success', 'Plan created successfully!');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function render()
    {

        return view('livewire.admin.pricing-plan.create-pricing-plan');

    }
}

