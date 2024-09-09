<?php

namespace App\Http\Livewire\Admin\Setting;

use Livewire\Component;
use App\Models\Admin\Coupon\Coupon;
use Livewire\WithPagination;

class DiscountList extends Component
{
    use WithPagination;

    public $search = '';
    protected $coupons;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['refreshCoupon' => 'handleRefreshCoupon','deleteCoupon'];

    public function handleRefreshCoupon(){
        $this->getCoupon();
    }

    public function getCoupon()
    {
        $this->coupons = Coupon::getCoupon()->paginate($this->perPage);

    }

    public function deleteCoupon($coupon_id){

        Coupon::deleteCoupon($coupon_id);

        toastr()->success('Coupon deleted');

    }

    public function render()
    {

        $this->getCoupon();

        return view('livewire.admin.setting.discount-list', [
            'coupons' => $this->coupons,
        ]);
    }
}
