<?php

namespace App\Http\Livewire\Admin\Podcast;

use App\Models\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Podcast extends Component
{

    use WithFileUploads, WithPagination;

    public $title, $audio_file, $podcastId, $thumbnail_file, $thumbnail_url;

    protected $listeners = ['toggleCreatePodcastFormModal' => 'resetForm', 'deletePodcast'];

    protected $rules = [
        'title' => 'required|string|max:200',
        'audio_file' => 'required|file|mimes:mp3,wav,aac,ogg,flac|max:204800', // 200MB limit (204800 KB)
        'thumbnail_file' => 'required|file|mimes:png,jpg,jpeg,gif|max:204800', // 200MB limit (204800 KB)
    ];

    protected $messages = [
        'title.required' => 'The title field is required.',
        'title.string' => 'The title must be a valid string.',
        'title.max' => 'The title must not exceed 200 characters.',

        'audio_file.required' => 'Please upload an audio file.',
        'audio_file.file' => 'The uploaded file must be a valid file.',
        'audio_file.mimes' => 'The audio must be in one of these formats: mp3, wav, aac, ogg, or flac.',
        'audio_file.max' => 'The audio file must not exceed 200MB.',

        'thumbnail_file.required' => 'Please upload an thumbnail file.',
        'thumbnail_file.file' => 'The uploaded file must be a valid file.',
        'thumbnail_file.mimes' => 'The thumbnail must be in one of these formats: png, jpg, jpeg, or gif.',
        'thumbnail_file.max' => 'The thumbnail file must not exceed 200MB.',
    ];

    public function getPodcasts()
    {
        return \App\Models\Admin\Podcast\Podcast::getPodcast();
    }

    public function submitForm()
    {

        DB::beginTransaction();

        try {

            $this->validate();

            $upload_id = Upload::uploadFile($this->audio_file, '', '', 'audio');

            $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

            \App\Models\Admin\Podcast\Podcast::createPodcast($this->title, $upload_id, $thumbnail_id);

            session()->flash('success', 'Audio File uploaded successfully.');

            $this->resetForm();

            $this->emit('closeModal');

            $this->emit('refreshPage');

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function editPodcastModal($id, $title, $audio_url)
    {

        $thumbnail_url = \App\Models\Admin\Podcast\Podcast::singlePodcast($id);

        $this->podcastId = $id;
        $this->title = $title;
        $this->audio_file = $audio_url;
        $this->thumbnail_url = $thumbnail_url['thumbnail_url'] ? $thumbnail_url['thumbnail_url']['path'] : null;

    }

    public function updatePodcast(Request $request)
    {

        DB::beginTransaction();

        try {

            \App\Models\Admin\Podcast\Podcast::updatePodcast($this->podcastId, $this->title);

            if ($request->hasFile('audio_file')) {

                $upload_id = Upload::uploadFile($this->audio_file, '', '', 'audio');

                $podcast = \App\Models\Admin\Podcast\Podcast::singlePodcast($this->podcastId);

                $podcast['audio_id'] = $upload_id;
            }

            session()->flash('success', 'Audio File uploaded successfully.');

            $this->emit('refreshPage');

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }
    }


    public function deletePodcast($podcastId)
    {
        try {

            \App\Models\Admin\Podcast\Podcast::deletePodcast($podcastId);

            session()->flash('success', 'Audio File delete successfully.');

            $this->render();

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function resetForm()
    {
        $this->reset('title', 'audio_file');
        $this->resetValidation();
    }

    public function render()
    {

        return view('livewire.admin.podcast.podcast', ['podcasts' => $this->getPodcasts()]);
    }
}
