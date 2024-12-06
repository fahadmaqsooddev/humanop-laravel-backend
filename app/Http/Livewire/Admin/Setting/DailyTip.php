<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\IntentionPlan\IntentionOption;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Admin\DailyTip\DailyTip as DailyTipModel;

class DailyTip extends Component
{
    use WithPagination;

    public $search = '';
    protected $tips;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['refreshDailyTips', 'deleteTip', 'updateSession'];
    public $title, $description, $code, $tip_id;

    public function refreshDailyTips()
    {
        $this->getTips();
    }

    public function getTips()
    {
        $this->tips = DailyTipModel::allTips()->paginate($this->perPage);

    }

    public function editTip($id, $code, $title, $description, $interval, $subscription, $min_point, $max_point)
    {
        $this->emit('updateEditTipValues', $id, $code, $title, $description, $interval, $subscription, $min_point, $max_point);
    }

    public function updateSession($type)
    {
        session()->flash('success', 'Daily Tip ' . $type . ' successfully.');
    }

    public function deleteTip($tip_id)
    {

        DailyTipModel::deleteDailyTip($tip_id);
    }

    public function render()
    {
        $this->getTips();
        return view('livewire.admin.setting.daily-tip', ['dailyTips' => $this->tips]);
    }
}
