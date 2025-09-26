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

class EditPricingPlan extends Component
{

    public $plan_name, $price, $plan_type, $plans, $plan_id, $plan, $plan_detail;

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

    public function mount($plan)
    {

        $this->plan_name = $plan->name;
        $this->price = $plan->price;
        $this->plan_type = $plan->billing_method;
        $this->plan_id = $plan->id;
        $this->plan_detail = $plan->plan_detail;
    }

    public function updatePlan()
    {

        DB::beginTransaction();

        try {

            Stripe::setApiKey(config('cashier.secret'));

            $getPlan = Plan::getSingleB2BPlan($this->plan_id);

            $oldPriceId = $getPlan['plan_id'];

            $newProductName = $this->plan_name;

            $oldPrice = Price::retrieve($oldPriceId);

            $productId = $oldPrice->product;

            $updatedProduct = Product::update($productId, [
                'name' => $newProductName,
            ]);

            $getPlan->update([
                'name' => $updatedProduct['name'],
                'plan_detail' => $this->plan_detail,
            ]);

            DB::commit();

            $this->emit('refreshPage');

            session()->flash('success', 'Plan update successfully!');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong: ' . $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.admin.pricing-plan.edit-pricing-plan');
    }
}
