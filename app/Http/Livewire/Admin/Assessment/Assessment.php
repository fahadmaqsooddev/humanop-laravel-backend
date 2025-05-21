<?php

namespace App\Http\Livewire\Admin\Assessment;

use App\Enums\Admin\Admin;
use App\Events\Admin\Assessment\ResetAssessment;
use App\Helpers\Helpers;
use App\Models\Admin\Notification\Notification;
use App\Models\Notification\PushNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Assessment extends Component
{

    use WithPagination;

    public $style_code = '', $feature_code = '', $style_color = '', $feature_color = '', $feature_number = '', $feature_carousel_index = '', $style_carousel_index = '', $style_number = '', $name = '', $email = '', $age = '';

    public $selectedStyleCells = [], $selectedFeatureCells = [];

    protected $paginationTheme = 'bootstrap', $perPage = 10, $assessments = [];

    protected $listeners = ['selectStyleCode', 'selectFeatureCode', 'selectStyleNumber', 'selectFeatureNumber', 'logInAdminAsUser', 'changeUserAssessmentStatus', 'resetAssessment'];

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
        $this->fill(request()->only('name', 'email', 'age', 'style_code', 'style_color', 'feature_code', 'feature_color', 'feature_carousel_index', 'style_carousel_index'));
        $this->searchFilter();
    }

    public function updated($field)
    {
        if (in_array($field, ['name', 'email', 'age'])) {
            $this->resetPage();
        }
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

    public function logInAdminAsUser($id = null)
    {

        $user = User::whereId($id)->first();

        Auth::guard('web')->logout();

        Auth::guard('web')->login($user);

        return redirect('client/dashboard');

    }

    public function resetAssessment($assessmentId)
    {

        try {

            $assessment = \App\Models\Assessment::resetAssessmentStatus($assessmentId);

            if ($assessment['reset_assessment'] == 1) {

                $heading = 'Reset Assessment';

                $message = 'The assessment has been successfully reset.';

                $user = User::getSingleUser($assessment['user_id']);

                $deviceToken = $user['device_token'];

                $notification = PushNotification::getSingleNotification($user['id']);

                if ($notification['reset_assessment'] == 1) {

                    event(new ResetAssessment($assessment['user_id'], $heading, $message));

                    Helpers::OneSignalApiUsed($user['id'], $heading, $message);

                    Notification::createNotification($heading, $message, $deviceToken, $assessment['user_id'], 1, Admin::RESET_ASSESSMENT_NOTIFICATION, Admin::B2C_NOTIFICATION);

                }
            }

            session()->flash('success', "Reset Assessment updated successfully");

            $this->render();

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function render()
    {
        $this->searchFilter();

        return view('livewire.admin.assessment.assessment', [

            'assessments' => $this->assessments,
            'selectedStyleCells' => $this->selectedStyleCells,
            'selectedFeatureCells' => $this->selectedFeatureCells,
        ]);
    }


}
