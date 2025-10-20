<?php

namespace App\Http\Livewire\Admin\OptimizationPlan;

use Livewire\Component;
use Livewire\WithPagination;

class NintyDaysOptimizationPlan extends Component
{

    use WithPagination;

    public $plan_id, $condition, $priority, $ninty_days_plan, $day1_30, $day31_60, $day61_90;

    public $search = '';
    protected $plans;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['updateContent'];

    public function updateOptimizationModal($id, $condition, $priority, $ninty_days_plan, $day1_30, $day31_60, $day61_90)
    {
        $this->plan_id = $id;
        $this->priority = $priority;
        $this->condition = $condition;
        $this->ninty_days_plan = $ninty_days_plan;
        $this->day1_30 = $day1_30;
        $this->day31_60 = $day31_60;
        $this->day61_90 = $day61_90;

        $this->emit('loadEditorsData', [
            'ninty_days_plan' => $ninty_days_plan,
            'day1_30' => $day1_30,
            'day31_60' => $day31_60,
            'day61_90' => $day61_90,
        ]);
    }

    public function updateOptimizationPlan()
    {
        try {


            \App\Models\Admin\Plan\OptimizationPlan::updateNintyDaysOptimizationPlan($this->priority, $this->ninty_days_plan, $this->day1_30, $this->day31_60, $this->day61_90);

            session()->flash('success', "{$this->priority} Optimization PLan update successfully.");


        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function getOptimizationPlans()
    {

        $this->plans = \App\Models\Admin\Plan\OptimizationPlan::nintyDaysOptimizationPlans()->paginate($this->perPage);

    }

    public function render()
    {

        $this->getOptimizationPlans();

        return view('livewire.admin.optimization-plan.ninty-days-optimization-plan', ['optimizationPlans' => $this->plans]);
    }
}
