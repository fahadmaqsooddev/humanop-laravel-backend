<?php

namespace App\Http\Livewire\Admin\Podcast;

use Livewire\Component;
use Livewire\WithFileUploads;

class Podcast extends Component
{

    use WithFileUploads;

    public $podcast_url, $latest_podcast;

    protected $listeners = ['toggleCreatePodcastFormModal' => 'resetForm'];

    protected $rules = [
        'podcast_url' => ['required','url', 'regex:/(app.hiro.fm)/']
    ];

    protected $messages = [
        'podcast_url.required' => 'Podcast url is required',
        'podcast_url.url' => 'Podcast must be a url',
        'podcast_url.regex' => 'Podcast url must be a hiro.fm embedded link',
    ];

    public function updatePodcast(){

        $this->validate();

        \App\Models\Admin\Podcast\Podcast::updatePodcastUrl($this->podcast_url);

        $this->emit('toggleCreatePodcastFormModal');

        toastr()->success( "Podcast updated successfully");
    }

    public function resetForm()
    {
        $this->reset('podcast_url'); // Clear the podcast_audio input
        $this->resetValidation(); // Clear validation errors
    }

    public function render()
    {

        $this->latest_podcast = \App\Models\Admin\Podcast\Podcast::adminLatestPodcastUrl();

        return view('livewire.admin.podcast.podcast');
    }
}
