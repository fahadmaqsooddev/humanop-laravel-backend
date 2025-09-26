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

class PricingPlanLists extends Component
{

    public $plan_name, $price, $plans, $plan_id;

    protected $listeners = ['activeInactivePlanModal'];

    public function getPlans()
    {
        $this->plans = Plan::getB2CPlans();
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

                Price::update($getPlan['plan_id'], [
                    'active' => true,
                ]);

                Product::update($productId, [
                    'active' => true,
                ]);

                $getPlan->update(['status' => 1]);

                DB::commit();

                session()->flash('success', 'Plan activated successfully!');

            }

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong: ' . $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function render()
    {
        $this->getPlans();

        return view('livewire.admin.pricing-plan.pricing-plan-lists', ['plans' => $this->plans]);

    }

}
