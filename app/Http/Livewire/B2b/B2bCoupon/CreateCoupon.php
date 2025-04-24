<?php

namespace App\Http\Livewire\B2b\B2bCoupon;

use App\Helpers\Helpers;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Stripe\Stripe;

class CreateCoupon extends Component
{

    public $limit;

    protected $rules = [
        'limit' => 'required|numeric|min:100',
    ];

    protected $messages = [
        'limit.required' => 'The limit is required.',
        'limit.numeric' => 'The limit must be a number.',
        'limit.min' => 'The limit must be at least 100 characters.',
    ];


    public function submitForm()
    {

        DB::beginTransaction();

        try {

            Stripe::setApiKey(config('cashier.secret'));

            \Stripe\Coupon::create([
                'percent_off' => $this->limit,
                'duration' => 'once',
            ]);

            $this->resetForm();

            session()->flash('success', 'Coupon created successfully!');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong: ' . $exception->getMessage());

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['limit']);
    }

    public function render()
    {
        return view('livewire.b2b.b2b-coupon.create-coupon');
    }
}
