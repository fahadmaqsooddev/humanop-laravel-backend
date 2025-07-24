<?php

namespace App\Http\Livewire\Admin\AnnouncementNews;

use App\Models\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Admin\AnnouncementNews\AnnouncementNews as AnnouncementNewsModel;

class AnnouncementNews extends Component
{

    public $title, $description;

    protected $listeners = ['deleteAnnouncementNews'];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ];

    protected $messages = [
        'title.required' => 'The name field is required.',
        'title.string' => 'The name must be a valid string.',
        'title.max' => 'The name must not exceed 255 characters.',
        'description.required' => 'The description field is required.',
        'description.string' => 'The description must be valid text.',
    ];

    public function getAnnouncementNews()
    {
        try {

            return AnnouncementNewsModel::getAnnouncementNews();

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());
        }

    }

    public function submitForm()
    {
        DB::beginTransaction();

        try {

            $validatedData = $this->validate();

            AnnouncementNewsModel::createAnnouncementNews($validatedData);

            $this->resetForm();

            $this->dispatchBrowserEvent('reset-summernote');

            $this->dispatchBrowserEvent('reset-file-input');

            session()->flash('success', ' Announcement & News Created successfully.');

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }


    public function editAnnouncementModal($id, $title, $description)
    {
        $this->tutorialId = $id;
        $this->title = $title;
        $this->description = $description;

        $this->dispatchBrowserEvent('set-edit-description', ['content' => $description]);

    }

    public function updateForm(Request $request)
    {
        DB::beginTransaction();

        try {

            AnnouncementNewsModel::updateAnnouncementNews($this->tutorialId, $this->title, $this->description);

            session()->flash('success', ' Announcement & News Updated successfully.');

            $this->render();

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function deleteAnnouncementNews($id)
    {

        AnnouncementNewsModel::deleteAnnouncementNews($id);

        $this->resetForm();

        session()->flash('success', 'Announcement & News Deleted Successfully.');


    }

    public function resetForm()
    {

        $this->title = '';
        $this->description = '';

    }

    public function render()
    {

        return view('livewire.admin.announcement-news.announcement-news', ['announcements' => $this->getAnnouncementNews()]);

    }

}
