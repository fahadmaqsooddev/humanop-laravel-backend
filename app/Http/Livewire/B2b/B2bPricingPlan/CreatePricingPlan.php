<?php

namespace App\Http\Livewire\B2b\B2bPricingPlan;

use Livewire\Component;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class CreatePricingPlan extends Component
{

    public $plan_name,$price,$plan_type,$plan_desc;

    public function submitForm(){

        Stripe::setApiKey(config('cashier.secret'));

        $product = Product::create([
            'name' => $this->plan_name,
            'description' => $this->plan_desc,
        ]);

        $price = Price::create([
            'unit_amount' => $this->price,
            'currency' => 'usd',
            'recurring' => ['interval' => $this->plan_type],
            'product' => $product->id,
        ]);

        dd($product, $price);
//        return response()->json([
//            'product' => $product,
//            'price' => $price
//        ]);
    }

    public function render()
    {
        return view('livewire.b2b.b2b-pricing-plan.create-pricing-plan');
    }
}
