<?php

namespace App\Http\Livewire\Admin\OptimizationPlan;

use Livewire\Component;
use Livewire\WithPagination;

class FourteenDaysOptimizationPlan extends Component
{

    use WithPagination;

    public $plan_id, $condition, $priority, $fourteen_days_plan;

    public $search = '';
    protected $plans;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['updateContent'];

    public function updateOptimizationModal($id, $condition, $priority, $fourteen_days_plan)
    {
        $this->plan_id = $id;
        $this->priority = $priority;
        $this->condition = $condition;
        $this->fourteen_days_plan = $fourteen_days_plan;
        $this->emit('fourteenDaysPlanUpdated', $this->fourteen_days_plan);

    }

    public function updateOptimizationPlan()
    {
        try {

            \App\Models\Admin\Plan\OptimizationPlan::updateOptimizationPlan($this->priority, $this->fourteen_days_plan);

            session()->flash('success', "{$this->priority} Optimization PLan update successfully.");


        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function getOptimizationPlans()
    {

        $this->plans = \App\Models\Admin\Plan\OptimizationPlan::fourteenDaysOptimizationPlans()->paginate($this->perPage);

    }

    public function render()
    {
        $this->getOptimizationPlans();

        return view('livewire.admin.optimization-plan.fourteen-days-optimization-plan',['optimizationPlans' => $this->plans]);
    }
}
