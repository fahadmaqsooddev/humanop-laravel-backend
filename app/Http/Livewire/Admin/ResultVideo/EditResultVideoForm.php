<?php

namespace App\Http\Livewire\Admin\ResultVideo;

use App\Helpers\Helpers;
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
    public $image_file;

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

            $this->validate([
                'image_file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $resultVideo = ResultVideo::getSingleVideo($this->select_video['id']);

            $resultVideo->public_name = $this->select_video['public_name'] ?? $resultVideo->public_name;

            $resultVideo->video_embed_link = $this->select_video['video_embed_link'];

            if (!empty($this->image_file)) {

                $resultVideo->image_id =  Upload::uploadFile($this->image_file, 200, 200, 'base64Image', 'png', true);

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
