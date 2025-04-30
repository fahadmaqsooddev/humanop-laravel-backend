<?php

namespace App\Http\Livewire\B2b\B2bPricingPlan;

use Livewire\Component;
use Stripe\Price;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use App\Models\Client\Plan\Plan;
use Stripe\Product;
use Stripe\Stripe;

use Illuminate\Support\Facades\DB;

class CreatePricingPlan extends Component
{

    public $plan_name, $price, $plan_type, $team_members, $plans, $plan_id;

    protected $listeners = ['activeInactivePlanModal'];

    public function getPlans()
    {
        $this->plans = Plan::getB2BPlans();
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

            Stripe::setApiKey(config('cashier.secret'));

            $getPlan = Plan::getSingleB2BPlan($planId);

            // Step 1: Retrieve the price
            $price = Price::retrieve($getPlan['plan_id']);

            $productId = $price->product;

            if ($getPlan && $getPlan['status'] == 1) {

                // Step 2: Deactivate the price
                Price::update($getPlan['plan_id'], [
                    'active' => false,
                ]);

                // Step 3: Deactivate the product
                Product::update($productId, [
                    'active' => false,
                ]);

                $getPlan->update(['status' => 0]);

                DB::commit();

                session()->flash('success', 'Plan Inactive successfully!');

            } else {

                $b2bActivePlan = Plan::activeb2BPlans();

                if ($b2bActivePlan < 4)
                {
                    // Step 2: Deactivate the price
                    Price::update($getPlan['plan_id'], [
                        'active' => true,
                    ]);

                    // Step 3: Deactivate the product
                    Product::update($productId, [
                        'active' => true,
                    ]);

                    $getPlan->update(['status' => 1]);

                    DB::commit();

                    session()->flash('success', 'Plan activated successfully!');
                }
                else
                {
                    DB::rollBack(); // Important: Rollback if you can't proceed
                    session()->flash('error', 'You can only have a maximum of 4 active plans.');
                }

            }

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong: ' . $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function submitForm()
    {
        DB::beginTransaction();
        try {

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
                'plan_type' => Admin::B2B_PLAN,
                'no_of_team_members' => $this->team_members,
            ];

            Plan::storePlan($this->data);

            DB::commit();
            $this->resetForm();

            session()->flash('success', 'Plan created successfully!');

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
            $newAmount = $this->price; // in cents
            $interval = $this->plan_type;

            // Step 1: Get the existing price
            $oldPrice = Price::retrieve($oldPriceId);
            $productId = $oldPrice->product;

            // Step 2: Update the product name
            $updatedProduct = Product::update($productId, [
                'name' => $newProductName,
            ]);

            // Step 3: Create a new price
            $newPrice = Price::create([
                'unit_amount' => $newAmount * 100,
                'currency' => 'usd', // adjust as needed
                'recurring' => ['interval' => $interval],
                'product' => $productId,
            ]);

            // Step 4 (optional): Deactivate old price
            Price::update($oldPriceId, ['active' => false]);

            $getPlan->update([
                'plan_id' => $newPrice['id'],
                'name' => $updatedProduct['name'],
                'billing_method' => $newPrice['recurring']['interval'],
                'interval_count' => $newPrice['recurring']['interval_count'],
                'price' => $newAmount,
                'currency' => $newPrice['currency'],
                'plan_type' => Admin::B2B_PLAN,
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
        $this->reset(['plan_name', 'price', 'plan_type', 'team_members']);
    }

    public function render()
    {

        $this->getPlans();

        return view('livewire.b2b.b2b-pricing-plan.create-pricing-plan', [
            'plans' => $this->plans
        ]);
    }
}
