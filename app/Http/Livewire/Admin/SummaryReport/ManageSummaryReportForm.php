<?php

namespace App\Http\Livewire\Admin\SummaryReport;

use Livewire\Component;
use App\Models\Admin\AssessmentIntro\AssessmentIntro;
use App\Traits\HandlesValidationErrors;
use App\Http\Requests\Admin\ManageCode\UpdateCodeRequest;
class ManageSummaryReportForm extends Component
{

    
    use HandlesValidationErrors;
    public $select_code;

    public function mount($summary)
    {
        $this->select_code = $summary->toArray();

    }

    public function updateSummaryReport()
    {
        

        if($this->customValidation(new UpdateCodeRequest($this->select_code),$this->select_code)){return;};
        try {

            $keysToKeep = ['name', 'public_name', 'code', 'type', 'text'];
            $data = array_intersect_key($this->select_code, array_flip($keysToKeep));

            AssessmentIntro::updateIntro($data, $this->select_code['id']);

            

            session()->flash('success', 'Summary Report updated successfully.');

        }catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    

    public function render()
    {
        return view('livewire.admin.summary-report.manage-summary-report-form');
    }
}
