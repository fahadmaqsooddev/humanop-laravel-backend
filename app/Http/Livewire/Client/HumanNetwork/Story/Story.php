<?php

namespace App\Http\Livewire\Client\HumanNetwork\Story;

use App\Helpers\Helpers;
use App\Models\Client\StoryView\StoryView;
use App\Models\Upload\Upload;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class Story extends Component
{
    use WithFileUploads;

    public $user, $story_photo, $logged_in_user_stories, $story;

    public $stories, $story_users = [];

    protected $listeners = ['addStoryModal' => 'toggleCreateStoryModal', 'viewStoryModal' => 'toggleViewStoryModal'];

    protected $rules = [
        'story_photo' => 'required',
//        'video' => 'nullable|mimetypes:video/mp4|max:10240',
    ];

    protected $messages = [
        'story_photo.required' => 'story is required',
        'story_photo.image' => 'story must be an image',
        'story_photo.mimes' => 'story mimes must be (jpg,png,jpeg)',
        'story_photo.max' => "story max size is 3MB's",
    ];

    public function toggleCreateStoryModal(){

        $this->resetValidation();

        $this->emit('toggleCreateStoryFormModal');
    }

    public function viewStoryModal($user_id = null){

        $this->stories = \App\Models\Client\Story\Story::userStories($user_id);

        StoryView::addStoryView($this->stories->id);

        $this->emit('toggleViewStoryFormModal');
    }

    public function toggleViewStoryModal(){

        $this->emit('toggleViewStoryFormModal');
    }

    public function uploadStory(){

        $this->validate();

        $upload_id = "";

        if ($this->story_photo){

            $extension = $this->story_photo->extension() ?? null;

            if ($extension){

                $thumbnail_height_width = $extension === 'mp4' ? "" : 200;

                $type = $extension === 'mp4' ? "video" : "base64Image";

                $resize =  $extension === 'mp4' ? false : true;

                $upload_id = Upload::uploadFile($this->story_photo, $thumbnail_height_width, $thumbnail_height_width, $type, $extension, $resize);

            }

            $data['upload_id'] = $upload_id;

            $data['file_type'] = $type === 'video' ? "video" : "image";

            \App\Models\Client\Story\Story::addStory($data);

            $this->emit('toggleCreateStoryFormModal');

            toastr()->success('Story Uploaded successfully');

//            session()->flash('success', );
//
//            $this->emit('hideSuccessAlert');

        }else{

            toastr()->error("Something went wrong");
        }

    }

    public function deleteStory($id){

        if(\App\Models\Client\Story\Story::where('user_id', Helpers::getWebUser()->id)->whereId($id)->delete()){

            $this->emit('toggleViewStoryFormModal');

            toastr()->success('Story deleted successfully');
        }

    }

    public function render()
    {

        $this->user = Helpers::getWebUser();

        $this->story_users = User::storyUsers();

        $this->logged_in_user_stories = \App\Models\Client\Story\Story::loggedInUserStory();

        return view('livewire.client.human-network.story.story');
    }
}
