<?php

namespace App\Http\Livewire\Admin\Podcast;

use App\Models\Upload\Upload;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Podcast extends Component
{

    use WithFileUploads, WithPagination;

    public $title, $audio_file;

    protected $listeners = ['toggleCreatePodcastFormModal' => 'resetForm'];

    protected $rules = [
        'title' => 'required|string|max:200',
        'audio_file' => 'required|file|mimes:mp3,wav,aac,ogg,flac|max:204800', // 200MB limit (204800 KB)
    ];

    protected $messages = [
        'title.required' => 'The title field is required.',
        'title.string' => 'The title must be a valid string.',
        'title.max' => 'The title must not exceed 200 characters.',

        'audio_file.required' => 'Please upload an audio file.',
        'audio_file.file' => 'The uploaded file must be a valid file.',
        'audio_file.mimes' => 'The audio must be in one of these formats: mp3, wav, aac, ogg, or flac.',
        'audio_file.max' => 'The audio file must not exceed 200MB.',
    ];

    public function getPodcasts()
    {
        return \App\Models\Admin\Podcast\Podcast::getPodcast();
    }

    public function submitForm()
    {
        $this->validate();

        $upload_id = Upload::uploadFile($this->audio_file, '', '', 'audio');

        \App\Models\Admin\Podcast\Podcast::createPodcast($this->title, $upload_id);

        session()->flash('success', 'Audio File uploaded successfully.');

        $this->resetForm();

    }
    public function updatePodcast(){

        $this->validate();

        \App\Models\Admin\Podcast\Podcast::updatePodcastUrl($this->podcast_url);

        $this->emit('toggleCreatePodcastFormModal');

        toastr()->success( "Podcast updated successfully");
    }

    public function resetForm()
    {
        $this->reset('title', 'audio_file');
        $this->resetValidation();
    }

    public function render()
    {

        $podcasts = $this->getPodcasts();

        return view('livewire.admin.podcast.podcast', ['podcasts' => $podcasts]);
    }
}
