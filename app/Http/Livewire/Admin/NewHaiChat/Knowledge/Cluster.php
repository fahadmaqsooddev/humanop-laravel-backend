<?php

namespace App\Http\Livewire\Admin\NewHaiChat\Knowledge;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\HaiChatEmbedding;
use Illuminate\Support\Facades\Storage;
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

        return view('livewire.admin.new-hai-chat.knowledge.cluster');
    }

    public function deleteCluster($id){

        EmbeddingGroup::whereId($id)->delete();

    }

    public function updatedSearchCluster($value){

        $this->search_clusters = $value;
    }

    public function updatedBulkOption($value){

        if ($value == 1) { // retrain

            foreach ($this->selectedClusters as $cluster){

                $this->reTrainClusterEmbeddings($cluster);

            }

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

    public function reTrainClusterEmbeddings($cluster_id){

        $embeddings = HaiChatEmbedding::whereHas('group', function ($query)use ($cluster_id){

            $query->where('group_id', $cluster_id);

        })->where('ready_for_training', 1)->get();

        foreach ($embeddings as $embedding){

            $embedding_text = Storage::disk('local')->get('training_files/' . "retrain-embedding-" . $embedding->id . '.txt');

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

            $body = ['loc' => $subFolder, 'request_id' => $embedding->request_id, 'text' => $embedding_text];

            GuzzleHelpers::sendRequestFromGuzzle('post', 'update_embedding', $body);

            $embedding->update(['ready_for_training' => 0]);

        }

    }
}
