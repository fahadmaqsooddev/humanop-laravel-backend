<?php

namespace App\Http\Livewire\Admin\Podcast;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Upload\Upload;
use App\Helpers\Helpers;
use App\Models\Admin\Podcast\Podcast as PodcastVideo;

class Podcast extends Component
{

    use WithFileUploads;

    public $podcast_video;
    public $video;

    protected $listeners = ['toggleCreatePodcastFormModal' => 'resetForm'];

    protected $rules = [
        'podcast_video' => 'nullable|mimetypes:video/mp4|max:10240'
    ];

    public function mount()
    {
        $podcast = PodcastVideo::getPodcast();

        $this->video = $podcast ? $podcast['video_url']['path'] : '';
    }

    public function updatePodcast(){

        $this->validate();

        $data = [];

        if ($this->podcast_video){

            $upload_id = Upload::uploadFile($this->podcast_video, '', '', 'video');

            $data['upload_id'] = $upload_id;
        }

        PodcastVideo::createVideo($data);

        $this->emit('toggleCreatePodcastFormModal');

        session()->flash('success', "Podcast updated successfully");
    }

    public function resetForm()
    {
        $this->reset('podcast_video'); // Clear the podcast_video input
        $this->resetValidation(); // Clear validation errors
    }

    public function render()
    {

        $this->mount();

        return view('livewire.admin.podcast.podcast');
    }
}
