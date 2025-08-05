<?php

namespace App\Http\Livewire\Admin\ResultVideo;

use App\Models\Admin\Code\ResultVideo;
use App\Models\Upload\Upload;
use App\Traits\HandlesValidationErrors;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditResultVideoForm extends Component
{
    use WithFileUploads;

    use HandlesValidationErrors;

    public $select_video;

    protected $listeners = ['videoUpdated' => 'loadResultVideo'];

    public function mount($video)
    {

        $this->select_video = $video->toArray();

    }

    public function loadResultVideo()
    {
        $this->select_video = ResultVideo::getSingleVideo($this->select_video['id'])->toArray();
    }

    public function updateResultVideo()
    {
        try {
            $resultVideo = ResultVideo::getSingleVideo($this->select_video['id']);

            $resultVideo->public_name = $this->select_video['public_name'] ?? $resultVideo->public_name;

            if (!empty($this->select_video['video_file'])) {

                $upload_id = Upload::uploadFile($this->select_video['video_file'], '', '', 'video');

                $resultVideo->video_upload_id = $upload_id;

            }

            $resultVideo->save();

            $this->dispatchBrowserEvent('pageReload');

            session()->flash('success', 'Result Video updated successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', 'Update failed: ' . $exception->getMessage());

        }

    }

    public function render()
    {

        return view('livewire.admin.result-video.edit-result-video-form');
    }
}
