<?php

namespace App\Http\Livewire\Admin\NetworkTutorial;

use App\Models\Upload\Upload;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class NetworkTutorial extends Component
{
    use WithFileUploads;

    public $title, $icon, $description;

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

    public function mount()
    {

    }

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

                session()->flash('success', ' Network Tutorial Created successfully.');
            }

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function deleteTutorial($id)
    {

        \App\Models\NetworkTutorial\NetworkTutorial::deleteTutorial($id);

        session()->flash('success', 'Tutorial Deleted Successfully.');


    }

    public function resetForm(){

        $this->reset(['title','icon','description']);

    }
    public function render()
    {
        $tutorials = $this->getTutorials();

        return view('livewire.admin.network-tutorial.network-tutorial', ['tutorials' => $tutorials]);
    }
}
