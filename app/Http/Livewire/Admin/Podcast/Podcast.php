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

    public $podcast_audio;
    public $audio;

    protected $listeners = ['toggleCreatePodcastFormModal' => 'resetForm'];

    protected $rules = [
        'podcast_audio' => 'nullable|file|mimes:audio/mp3'
    ];

    public function mount()
    {
        $podcast = PodcastVideo::getPodcast();

        $this->audio = $podcast ? $podcast['audio_url']['path'] : '';

        dd($this->audio);
    }

    public function updatePodcast(){

//        $this->validate();

        $data = [];

        if ($this->podcast_audio){

            $upload_id = Upload::uploadFile($this->podcast_audio, '', '', 'audio');

            $data['upload_id'] = $upload_id;
        }

        PodcastVideo::createVideo($data);

        $this->emit('toggleCreatePodcastFormModal');

        session()->flash('success', "Podcast updated successfully");
    }

    public function resetForm()
    {
        $this->reset('podcast_audio'); // Clear the podcast_audio input
        $this->resetValidation(); // Clear validation errors
    }

    public function render()
    {

        $this->mount();

        return view('livewire.admin.podcast.podcast');
    }
}
