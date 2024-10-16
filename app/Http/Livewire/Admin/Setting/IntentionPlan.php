<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\Admin\Coupon\Coupon;
use App\Models\IntentionPlan\IntentionOption;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class IntentionPlan extends Component
{
    use WithPagination;

    public $search = '';
    protected $options;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['refreshIntentionPlanOption'];

    public function refreshIntentionPlanOption(){
        $this->getOption();
    }

    public function getOption()
    {
        $this->options = IntentionOption::allOptions()->paginate($this->perPage);
    }

//    public function deleteCoupon($coupon_id){
//
//        Coupon::deleteCoupon($coupon_id);
//
//        toastr()->success('Coupon deleted');
//
//    }

//    public function render()
//    {
//
//        $this->getCoupon();
//
//        return view('livewire.admin.setting.discount-list', [
//            'coupons' => $this->coupons,
//        ]);
//    }
    public function render()
    {


        $this->getOption();
        return view('livewire.admin.setting.intention-plan',['intentionOption' => $this->options]);
    }
}
