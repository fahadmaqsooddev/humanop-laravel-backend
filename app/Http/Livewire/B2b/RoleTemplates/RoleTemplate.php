<?php

namespace App\Http\Livewire\B2b\RoleTemplates;

use Livewire\Component;
use App\Models\IntentionPlan\IntentionOption;
use Livewire\WithPagination;
// use App\Models\Admin\DailyTip\DailyTip as DailyTipModel;
use App\Models\B2B\RoleTemplate as RoleTemplateModel;

class RoleTemplate extends Component
{

    use WithPagination;

    public $search = '';
    protected $tips;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['refreshDailyTips', 'deleteTemplate', 'updateSession'];
    public $title, $description, $code, $template_id;

    public function refreshDailyTips()
    {
        $this->getTemplate();
    }

    public function getTemplate()
    {
        // $this->tips = DailyTipModel::allTips()->paginate($this->perPage);
        $this->tips = RoleTemplateModel::allTemplate()->paginate($this->perPage);

    }

    public function editTemplate($id, $code, $title, $description, $interval, $subscription, $min_point, $max_point)
    {
        $this->emit('updateEditTemplateValues', $id, $code, $title, $description, $interval, $subscription, $min_point, $max_point);
    }

    public function updateSession($type)
    {
        session()->flash('success', 'Template ' . $type . ' successfully.');
    }

    public function deleteTemplate($template_id)
    {

        
        RoleTemplateModel::deleteTemplate($template_id);
    }

    public function render()
    {
        $this->getTemplate();
        return view('livewire.b2b.role-templates.role-template', ['dailyTemplates' => $this->tips]);
    }

    
   
}
