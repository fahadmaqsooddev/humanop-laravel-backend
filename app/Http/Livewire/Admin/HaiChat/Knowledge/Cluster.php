<?php

namespace App\Http\Livewire\Admin\HaiChat\Knowledge;

use App\Models\HAIChai\EmbeddingGroup;
use Livewire\Component;

class Cluster extends Component
{

    protected $listeners = ['deleteCluster'];

    public $clusters = [];

    public function render()
    {
        $this->clusters = EmbeddingGroup::allClusters();

        return view('livewire.admin.hai-chat.knowledge.cluster');
    }

    public function deleteCluster($id){

        EmbeddingGroup::whereId($id)->delete();

    }
}
