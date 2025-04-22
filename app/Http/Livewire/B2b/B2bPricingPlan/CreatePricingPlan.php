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

    public $month = '';
    public $year = '';
    public $plans;
    public $data = [];
    public $tab = 'month';


    public function mount()
    {
        $this->loadPlans();
    }

    public function selectTab($type)
    {
        $this->tab = $type;
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = [];
        Stripe::setApiKey(config('cashier.secret'));
        if ($this->tab === 'month') {


            $prices = Plan::getdashboadB2Bplans($this->tab);

            if (!empty($prices)) {
                foreach ($prices as $getPrice) {
                    // Step 1: Get the Price
                    $price = Price::retrieve($getPrice['plan_id']);

                    // Step 2: Get the associated Product
                    $product = Product::retrieve($price->product);

                    $this->plans[] = [
                        'price_id' => $price->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_amount' => $price->unit_amount,
                        'interval' => $price->recurring->interval ?? null,
                        'no_of_team_members' => $getPrice['no_of_team_members'],
                    ];


                }
            }

        } elseif ($this->tab === 'year') {


            $prices = Plan::getdashboadB2Bplans($this->tab);

            if (!empty($prices)) {
                foreach ($prices as $getPrice) {

                    $price = Price::retrieve($getPrice['plan_id']);


                    $product = Product::retrieve($price->product);

                    $this->plans[] = [
                        'price_id' => $price->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_amount' => $price->unit_amount,
                        'interval' => $price->recurring->interval ?? null,
                        'no_of_team_members' => $getPrice['no_of_team_members'],
                    ];


                }
            }


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

    public function resetForm()
    {
        $this->reset(['plan_name', 'price', 'plan_type', 'team_members']);
    }

    public function render()
    {


        return view('livewire.b2b.b2b-pricing-plan.create-pricing-plan');
    }
}
