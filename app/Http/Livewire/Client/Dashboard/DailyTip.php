<?php

namespace App\Http\Livewire\Client\Dashboard;

use App\Models\Admin\DailyTip\UserDailyTip;

use Livewire\Component;
use App\Models\Admin\DailyTip\DailyTip as UserTip;
class DailyTip extends Component
{
    public $tip,$isReadDailyTip,$userTipCreatedAt;
    protected $listeners = ['updateTip'];
    public function mount(){
        $this->getDailyTip();
    }
    public function getDailyTip(){
//        $this->tip = UserTip::checkTodayTip();
//        if($this->tip){
//            $userDailyTipDetail = UserDailyTip::userDailytip($this->tip['id']);
//            $this->isReadDailyTip = $userDailyTipDetail['is_read'];
//            $this->userTipCreatedAt = $userDailyTipDetail['created_at'];
//        }
    }
    public function updateTip(){
        $this->getDailyTip();
    }

    public function render()
    {
        return view('livewire.client.dashboard.daily-tip');
    }
}
