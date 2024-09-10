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

    public $user, $ageRange ,$profile_image, $is_abandon_assessment;

    protected $listeners = ['deleteAbandonAssessmentOnGenderChange'];

    public function mount($user)
    {
        $this->user = $user->toArray();

        $assessment = Assessment::where('user_id', Helpers::getWebUser()->id)->latest()->first();

        $this->is_abandon_assessment = $assessment ? $assessment->page > 0 ? true : false : false;
    }

    public function submitForm()
    {
        if($this->customValidation(new BasicSettingRequest($this->user),$this->user)){return;};

        try {
            $keysToKeep = ['first_name', 'last_name','email','age_min', 'age_max', 'gender', 'phone'];

            if ($this->profile_image){
                $upload_id = Upload::uploadFile($this->profile_image, 200, 200, 'base64Image','png', true);
                $this->user['image_id'] = $upload_id;
                array_push($keysToKeep, 'image_id');
            }

            $age = explode('-', $this->user['age_range']);
            $this->user['age_min'] = $age[0];
            $this->user['age_max'] = $age[1];

            $data = array_intersect_key($this->user, array_flip($keysToKeep));
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
