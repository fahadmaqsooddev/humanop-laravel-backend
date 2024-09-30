<?php

namespace App\Http\Livewire\Client\Setting;

use App\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\Upload\Upload;
use Livewire\Component;
use App\Models\User;
use App\Traits\HandlesValidationErrors;
use App\Http\Requests\Admin\Setting\BasicSettingRequest;
use Livewire\WithFileUploads;

class BasicSettingForm extends Component
{
    use WithFileUploads;
    use HandlesValidationErrors;

    public $user, $ageRange ,$profile_image, $is_abandon_assessment, $day, $month, $year;

    protected $listeners = ['deleteAbandonAssessmentOnGenderChange'];

    public function mount($user)
    {
        $this->user = $user->toArray();

        $date_of_birth = explode('-',$this->user['date_of_birth']);

        $this->day = intval($date_of_birth[2]);
        $this->month = intval($date_of_birth[1]);
        $this->year = intval($date_of_birth[0]);

        $assessment = Assessment::where('user_id', Helpers::getWebUser()->id)->latest()->first();

        $this->is_abandon_assessment = $assessment ? $assessment->page > 0 ? true : false : false;
    }

    public function submitForm()
    {
        if($this->customValidation(new BasicSettingRequest($this->user),$this->user)){return;};

        try {

            $keysToKeep = ['first_name', 'last_name','date_of_birth', 'gender', 'phone'];

            if ($this->profile_image){
                $upload_id = Upload::uploadFile($this->profile_image, 200, 200, 'base64Image','png', true);
                $this->user['image_id'] = $upload_id;
                array_push($keysToKeep, 'image_id');
            }

            $data = array_intersect_key($this->user, array_flip($keysToKeep));

            $data['date_of_birth'] = $this->year . '-' . $this->month . '-' . $this->day;

            User::updateUser($data, $this->user['id']);
            auth()->user()->refresh();

            $this->emit('userBasicSettingUpdated', auth()->user());

            session()->flash('success', 'User updated successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function deleteAbandonAssessmentOnGenderChange(){

        Assessment::deleteIncompleteAssessment();
    }

    public function render()
    {
        return view('livewire.client.setting.basic-setting-form');
    }
}
