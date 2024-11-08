<?php

namespace App\Http\Livewire\Admin\OptimizationPlan;

use Livewire\Component;
use Livewire\WithPagination;

class OptimizationPlan extends Component
{

    use WithPagination;

    public $plan_id, $condition, $priority, $content;

    public $search = '';
    protected $plans;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['updateContent'];

    public function updateOptimizationModal($id, $condition, $priority, $content)
    {
        $this->plan_id = $id;
        $this->priority = $priority;
        $this->condition = $condition;
        $this->content = $content;
        $this->emit('contentUpdated', $this->content);

    }

    public function updateOptimizationPlan()
    {
        try {

            \App\Models\Admin\Plan\OptimizationPlan::updateOptimizationPlan($this->priority, $this->content);

            session()->flash('success', "{$this->priority} Optimization PLan update successfully.");


        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function getOptimizationPlans()
    {

        $this->plans = \App\Models\Admin\Plan\OptimizationPlan::allOptimizationPlans()->paginate($this->perPage);
    }

    public function render()
    {
        $this->getOptimizationPlans();

        return view('livewire.admin.optimization-plan.optimization-plan',['optimizationPlans' => $this->plans]);
    }
}
