<?php

namespace App\Http\Livewire\Admin\NetworkTutorial;

use App\Models\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class NetworkTutorial extends Component
{
    use WithFileUploads;

    public $tutorialId, $tutorialIcon, $title, $icon, $description;

    protected $listeners=['deleteTutorial'];

    protected $rules = [
        'title' => 'required|string|max:255',
        'icon' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        'description' => 'required|string',
    ];

    protected $messages = [
        'title.required' => 'The name field is required.',
        'title.string' => 'The name must be a valid string.',
        'title.max' => 'The name must not exceed 255 characters.',

        'icon.required' => 'Please upload an icon image.',
        'icon.image' => 'The icon must be an image file.',
        'icon.mimes' => 'The icon must be a file of type: jpeg, png, jpg, svg, or webp.',
        'icon.max' => 'The icon must not be larger than 2MB.',

        'description.required' => 'The description field is required.',
        'description.string' => 'The description must be valid text.',
    ];

    public function getTutorials()
    {
        try {

            $tutroials =  \App\Models\NetworkTutorial\NetworkTutorial::allTutorials();

            return $tutroials;

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());
        }

    }

    public function submitForm()
    {
        DB::beginTransaction();

        try {

            $validatedData = $this->validate();

            $tutorial = \App\Models\NetworkTutorial\NetworkTutorial::createTutorial($validatedData);

            if (!empty($this->icon)) {

                $upload_id = Upload::uploadFile($this->icon, 200, 200, 'base64Image', 'png', true);

                $tutorial->icon_id = $upload_id;

                $tutorial->save();

                $this->resetForm();

                // Fire browser event to reset Summernote
                $this->dispatchBrowserEvent('reset-summernote');

                $this->dispatchBrowserEvent('reset-file-input');

                session()->flash('success', ' Network Tutorial Created successfully.');
            }

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function editTutorialModal($id, $title, $icon, $description)
    {
        $this->tutorialId = $id;
        $this->title = $title;
        $this->tutorialIcon = $icon;
        $this->description = $description;

        $this->dispatchBrowserEvent('set-edit-description', ['content' => $description]);

    }

    public function updateForm(Request $request)
    {
        DB::beginTransaction();

        try {

            \App\Models\NetworkTutorial\NetworkTutorial::updateTutorial($this->tutorialId, $this->title, $this->description);

            if (!empty($this->icon)) {

                $upload_id = Upload::uploadFile($this->icon, 200, 200, 'base64Image', 'png', true);

                $tutorial = \App\Models\NetworkTutorial\NetworkTutorial::getSingleTutorial($this->tutorialId);

                $tutorial->icon_id = $upload_id;

                $tutorial->save();
            }

            $this->tutorialIcon = $tutorial['icon_url']['url'];

            session()->flash('success', ' Network Tutorial Updated successfully.');

            $this->render();

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function deleteTutorial($id)
    {

        \App\Models\NetworkTutorial\NetworkTutorial::deleteTutorial($id);

        $this->resetForm();

        session()->flash('success', 'Tutorial Deleted Successfully.');


    }

    public function resetForm(){

        $this->title = '';
        $this->icon = '';
        $this->description = '';

    }

    public function render()
    {
        $tutorials = $this->getTutorials();

        return view('livewire.admin.network-tutorial.network-tutorial', ['tutorials' => $tutorials]);
    }
}
