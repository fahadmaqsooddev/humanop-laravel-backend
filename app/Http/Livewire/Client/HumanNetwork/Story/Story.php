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
        'story_photo' => 'required|image|mimes:jpg,png,jpeg|max:3072'
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

        Log::info($this->story_photo);

        if ($this->story_photo){

            $upload_id = Upload::uploadFile($this->story_photo, 200, 200, 'base64Image', 'png', true);

            Log::info(['uplo' => $upload_id]);

            $data['upload_id'] = $upload_id;

            \App\Models\Client\Story\Story::addStory($data);

            $this->emit('toggleCreateStoryFormModal');

            session()->flash('success', 'Story Uploaded successfully');

            $this->emit('hideSuccessAlert');

        }else{

            Log::info('Error');
        }

    }

    public function deleteStory($id){

        if(\App\Models\Client\Story\Story::where('user_id', Helpers::getWebUser()->id)->whereId($id)->delete()){

            $this->emit('toggleViewStoryFormModal');
        }

    }

    public function render()
    {

        $this->user = Helpers::getWebUser();

        $this->story_users = User::storyUsers()->sortBy(function ($storyUser){
            return $storyUser->is_viewed;
        });

        $this->logged_in_user_stories = \App\Models\Client\Story\Story::loggedInUserStory();

        return view('livewire.client.human-network.story.story');
    }
}
