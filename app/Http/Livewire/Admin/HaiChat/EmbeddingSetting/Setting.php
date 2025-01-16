<?php

namespace App\Http\Livewire\Admin\HaiChat\EmbeddingSetting;

use App\Models\HAIChai\EmbeddingSetting;
use App\Models\HAIChai\HaiChatEmbedding;
use Livewire\Component;

class Setting extends Component
{
    public $chunk, $embedding_id;

    public function getEmbedding()
    {
        $embedding = HaiChatEmbedding::singleEmbedding($this->embedding_id);

        $this->chunk = $embedding['chunks'] ?? null;
    }

    public function submitForm()
    {
        try {

            HaiChatEmbedding::updateEmbeddingChunks($this->embedding_id, $this->chunk);

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
