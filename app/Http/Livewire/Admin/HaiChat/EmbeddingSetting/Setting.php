<?php

namespace App\Http\Livewire\Admin\HaiChat\EmbeddingSetting;

use App\Models\HAIChai\EmbeddingSetting;
use Livewire\Component;

class Setting extends Component
{
    public $name, $chunk;

    public function getEmbedding()
    {
        $embedding = EmbeddingSetting::getEmbeddingSetting($this->name);

        $this->chunk = $embedding['chunk'] ?? null;
    }

    public function submitForm()
    {
        try {

            EmbeddingSetting::checkAndUpdateEmbedding($this->name, $this->chunk);

            session()->flash('success', "Embedding Chunk update Successfully.");

        }catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        $this->getEmbedding();

        return view('livewire.admin.hai-chat.embedding-setting.setting', ['chunk' => $this->chunk]);
    }
}
