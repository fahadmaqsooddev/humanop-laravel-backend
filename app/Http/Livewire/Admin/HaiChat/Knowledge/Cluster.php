<?php

namespace App\Http\Livewire\Admin\HaiChat\Knowledge;

use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\EmbeddingGroup;
use Livewire\Component;

class Cluster extends Component
{

    protected $listeners = ['deleteCluster'];

    public $clusters = [], $brains = [];

    public $brain_id, $search_clusters, $bulk_option, $selectedClusters = [];

    public function render()
    {
        $this->clusters = EmbeddingGroup::allClusters($this->search_clusters,$this->brain_id);

        $this->brains = Chatbot::all();

        return view('livewire.admin.hai-chat.knowledge.cluster');
    }

    public function deleteCluster($id){

        EmbeddingGroup::whereId($id)->delete();

    }

    public function updatedSearchCluster($value){

        $this->search_clusters = $value;
    }

    public function updatedBulkOption($value){

        if ($value == 1) { // retrain


        }elseif ($value == 2){ // delete

            $this->deleteBulkClusters();
        }

        $this->reset('selectedClusters','bulk_option');
    }

    public function selectAllClusters(){

        if(count($this->clusters) === count($this->selectedClusters)){

            $this->reset('selectedClusters');

        }else{

            $this->selectedClusters = collect($this->clusters)->pluck('id')->toArray();
        }

    }

    public function selectIndividualCluster($cluster_id){

        $search = array_search($cluster_id, $this->selectedClusters);

        if ($search !== false){

            unset($this->selectedClusters[$search]);

        }else{

            array_push($this->selectedClusters,$cluster_id);
        }

    }

    public function deleteBulkClusters(){

        foreach ($this->selectedClusters as $cluster_id){

            $this->deleteCluster($cluster_id);

        }

    }
}
