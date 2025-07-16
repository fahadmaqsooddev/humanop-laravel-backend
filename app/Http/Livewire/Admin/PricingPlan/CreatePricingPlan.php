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
    public $plan_name, $price, $plan_type, $plans, $plan_id;

    protected $listeners = ['activeInactivePlanModal'];

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

    public function getPlans()
    {
        $this->plans = Plan::getB2CPlans();
    }

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
            ];

            Plan::storePlan($this->data);

            DB::commit();

            $this->resetForm();

            session()->flash('success', 'Plan created successfully!');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function updatePlanModal($planId, $planName, $planPrice, $noOfMembers, $planType)
    {

        $this->plan_id = $planId;

        $this->plan_name = $planName;

        $this->price = $planPrice;

        $this->team_members = $noOfMembers;

        $this->plan_type = $planType;

    }

    public function activeInactivePlanModal($planId)
    {
        DB::beginTransaction();

        try {

            Stripe::setApiKey(config('cashier.b2c_secret'));

            $getPlan = Plan::getSingleB2BPlan($planId);

            $price = Price::retrieve($getPlan['plan_id']);

            $productId = $price->product;

            if ($getPlan && $getPlan['status'] == 1) {

                Price::update($getPlan['plan_id'], [
                    'active' => false,
                ]);

                Product::update($productId, [
                    'active' => false,
                ]);

                $getPlan->update(['status' => 0]);

                DB::commit();

                session()->flash('success', 'Plan Inactive successfully!');

            } else {

                $b2bActivePlan = Plan::activeb2BPlans();

//                if ($b2bActivePlan < 4) {

                    Price::update($getPlan['plan_id'], [
                        'active' => true,
                    ]);

                    Product::update($productId, [
                        'active' => true,
                    ]);

                    $getPlan->update(['status' => 1]);

                    DB::commit();

                    session()->flash('success', 'Plan activated successfully!');

//                } else {
//
//                    DB::rollBack();
//
//                    session()->flash('error', 'You can only have a maximum of 4 active plans.');
//
//                }

            }

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong: ' . $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }



    public function updateB2bPlan()
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
                'no_of_team_members' => $this->team_members,
            ]);

            DB::commit();

            $this->resetForm();

            session()->flash('success', 'Plan update successfully!');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong: ' . $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function resetForm()
    {
        $this->reset(['plan_name', 'price', 'plan_type']);
    }

    public function render()
    {
        $this->getPlans();

        return view('livewire.admin.pricing-plan.create-pricing-plan', ['plans' => $this->plans]);

    }
}

