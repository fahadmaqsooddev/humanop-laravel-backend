<?php

namespace App\Http\Livewire\Admin\Podcast;

use App\Models\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPodcast extends Component
{
    use WithFileUploads;

    public $podcast , $title , $audio_file , $thumbnail_file, $audio_url, $thumbnail_url, $podcastId;

    public function mount($podcast)
    {

        $this->$podcast = $podcast->toArray();
        $this->title = $podcast->title;
        $this->podcastId = $podcast->id;
        $this->audio_file = $podcast->audio_file;
        $this->thumbnail_file = $podcast->thumbnail_file;
        $this->audio_url = $podcast->audio_url;
        $this->thumbnail_url = $podcast->thumbnail_url['url'] ?? null;

    }

    public function updatePodcast(Request $request)
    {

        DB::beginTransaction();

        try {

            \App\Models\Admin\Podcast\Podcast::updatePodcast($this->podcastId, $this->title);

            if ($this->audio_file) {

                $upload_id = Upload::uploadFile($this->audio_file, '', '', 'audio');

                $podcast = \App\Models\Admin\Podcast\Podcast::singlePodcast($this->podcastId);

                $podcast['audio_id'] = $upload_id;

                $podcast->save();

                DB::commit();
            }

            if ($this->thumbnail_file) {

                $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

                $podcast = \App\Models\Admin\Podcast\Podcast::singlePodcast($this->podcastId);

                $podcast['thumbnail_id'] = $thumbnail_id;

                $podcast->save();

                DB::commit();
            }

            session()->flash('success', 'Audio File uploaded successfully.');

            $this->emit('refreshPage');

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }
    }

    public function render()
    {
        return view('livewire.admin.podcast.edit-podcast');
    }
}
