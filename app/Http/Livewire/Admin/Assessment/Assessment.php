<?php

namespace App\Http\Livewire\Admin\Assessment;

use App\Enums\Admin\Admin;
use App\Events\Admin\Assessment\ResetAssessment;
use App\Helpers\Helpers;
use App\Models\Admin\Notification\Notification;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Assessment extends Component
{

    use WithPagination;

    public $style_code = '';
    public $feature_code = '';
    public $style_color = '';
    public $feature_color = '';
    public $feature_number = '';
    public $feature_carousel_index = '';
    public $style_carousel_index = '';
    public $style_number = '';
    public $name = '';
    public $email = '';
    public $age = '';
    public $perPage = 10;
    public $selectedStyleCells = [];
    public $selectedFeatureCells = [];
    protected $assessments = [];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['selectStyleCode', 'selectFeatureCode','selectStyleNumber','selectFeatureNumber','logInAdminAsUser','changeUserAssessmentStatus','resetAssessment'];

    protected $updatesQueryString = [
        'name' => ['except' => ''],
        'email' => ['except' => ''],
        'age' => ['except' => ''],
        'style_code' => ['except' => ''],
        'feature_code' => ['except' => ''],
        'style_color' => ['except' => ''],
        'feature_color' => ['except' => ''],
        'feature_carousel_index' => ['except' => ''],
        'style_carousel_index' => ['except' => ''],
    ];

    public function mount()
    {
        $this->fill(request()->only('name', 'email', 'age', 'style_code', 'style_color', 'feature_code', 'feature_color','feature_carousel_index','style_carousel_index'));
        $this->searchFilter();
    }

    public function updated()
    {
        $this->searchFilter();
    }

    public function updatedName($value)
    {
        // Debug the updated value
        dd($value); // This will display the updated value when 'name' changes
    }
    public function selectStyleCode($select_style_code, $select_style_code_color)
    {
        $this->selectedStyleCells[$select_style_code] = $select_style_code_color;
        $this->style_code = $select_style_code;
        $this->style_color = $select_style_code_color;
        $this->style_number = '';
        $this->searchFilter();
    }

    public function selectFeatureCode($select_feature_code, $select_feature_code_color)
    {
        $this->selectedFeatureCells[$select_feature_code] = $select_feature_code_color;
        $this->feature_code = $select_feature_code;
        $this->feature_color = $select_feature_code_color;
        $this->feature_number = '';
        $this->searchFilter();
    }

    public function selectFeatureNumber($selectNum, $index, $selectColor, $selectCode)
    {

        $this->feature_number = $selectNum;
        $this->feature_code = $selectCode;
        $this->feature_color = $selectColor;
        $this->feature_carousel_index = $index;

        $this->searchFilter();
    }

    public function selectStyleNumber($selectNum, $index, $selectColor, $selectCode)
    {
        $this->style_number = $selectNum;
        $this->style_color = $selectColor;
        $this->style_code = $selectCode;
        $this->style_carousel_index = $index;
        $this->searchFilter();
    }

    public function searchFilter()
    {
        $this->assessments = \App\Models\Assessment::allAssessment($this->name, $this->email, $this->age, $this->style_code, $this->style_color, $this->style_number, $this->feature_code, $this->feature_color, $this->feature_number);
    }

    public function logInAdminAsUser($id = null){

        $user = User::whereId($id)->first();

        Auth::guard('web')->logout();

        Auth::guard('web')->login($user);

        return redirect('client/dashboard');

    }

    public function resetAssessment($assessmentId)
    {

        $assessment = \App\Models\Assessment::resetAssessmentStatus($assessmentId);


        if ($assessment['reset_assessment'] == 1)
        {
            $heading = 'Reset Assessment';
            $message = 'The assessment has been successfully reset.';

            $user = User::getSingleUser($assessment['user_id']);

            $deviceToken = $user['device_token'];

            event(new ResetAssessment($assessment['user_id'], $heading, $message));

            Helpers::OneSignalApiUsed($user['id'], $heading, $message);

            Notification::createNotification($heading, $message, $deviceToken, $assessment['user_id'], 1, Admin::RESET_ASSESSMENT_NOTIFICATION,Admin::B2C_NOTIFICATION);
        }

        session()->flash('success', "Reset Assessment updated successfully");

        $this->render();
    }

    public function render()
    {
        return view('livewire.admin.assessment.assessment', [

            'assessments' => $this->assessments,
            'selectedStyleCells' => $this->selectedStyleCells,
            'selectedFeatureCells' => $this->selectedFeatureCells,
        ]);
    }


}
