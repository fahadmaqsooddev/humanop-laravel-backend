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

    public $plan_name, $price, $plan_type, $team_members;
    public $data = [];

    public function submitForm()
    {
        DB::beginTransaction();
        try {

            Stripe::setApiKey(config('cashier.secret'));

            $product = Product::create([
                'name' => $this->plan_name,
//                'description' => $this->plan_desc,
            ]);

            $price = Price::create([
                'unit_amount' => $this->price,
                'currency' => 'usd',
                'recurring' => ['interval' => $this->plan_type],
                'product' => $product->id,
            ]);

            $this->data = [
                'plan_id' => $price['id'],
                'name' => $product['name'],
                'billing_method' => $price['recurring']['interval'],
                'interval_count' => $price['recurring']['interval_count'],
                'price' => $price['unit_amount'],
                'currency' => $price['currency'],
                'plan_type' => Admin::B2B_PLAN,
                'team_members' => $this->team_members,
            ];

            Plan::storePlan($this->data);

            DB::commit();

            session()->flash('success', 'Plan created successfully!');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong: ' . $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());
        }


    }

    public function render()
    {
        return view('livewire.b2b.b2b-pricing-plan.create-pricing-plan');
    }
}
