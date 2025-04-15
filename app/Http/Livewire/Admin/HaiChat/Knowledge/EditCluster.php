<?php

namespace App\Http\Livewire\Admin\HaiChat\Knowledge;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditCluster extends Component
{

    public $embeddings = [], $selectedEmbeddings = [], $selectedKnowledgeSourceIds = [], $selectedKnowledgeSource = [],
        $selectedConnectedEmbeddings = [], $currentEmbeddings = [], $selectedCurrentEmbeddings = [];

    public $name, $description, $search_embedding, $search_connected_embedding ,$search_current_embedding, $bulk_option, $current_bulk_option, $connected_bulk_option, $updateEmbeddingText, $cluster_id;

    protected $rules = [
        'name' => 'required|max:50',
        'description' => 'required|max:200'
    ];

    protected $messages = [
        'name.required' => 'Cluster name is required.',
        'description.required' => 'Cluster description is required.'
    ];

    protected $listeners = ['deleteCurrentEmbedding','deleteEmbedding'];

    public function updatedBulkOption(int $value){

        if ($value === 1){ // Add to Cluster

            foreach ($this->selectedEmbeddings as $embedding){

                array_push($this->selectedKnowledgeSourceIds,$embedding);

            }

            $this->reset('selectedEmbeddings','bulk_option');

        }
    }

    public function updatedConnectedBulkOption(int $value){

        if ($value === 1){ // Remove from Cluster

            foreach ($this->selectedConnectedEmbeddings as $embedding){

                $search = array_search($embedding, $this->selectedKnowledgeSourceIds);

                if ($search !== false){

                    unset($this->selectedKnowledgeSourceIds[$search]);
                }

            }

            $this->reset('selectedConnectedEmbeddings','connected_bulk_option');

        }

    }

    public function updatedCurrentBulkOption(int $value){

        if ($value === 1){ // Remove from Cluster

            foreach ($this->selectedCurrentEmbeddings as $embedding){

                GroupEmbedding::removeEmbeddingsFromCluster($this->cluster_id, $embedding);
            }

            $this->reset('selectedCurrentEmbeddings','current_bulk_option');

        }

    }

    public function render()
    {

        $cluster = EmbeddingGroup::whereId($this->cluster_id)->first();

        if ($cluster){

            $this->name = $cluster['name'];
            $this->description = $cluster['description'];

        }

        $this->currentEmbeddings = GroupEmbedding::groupEmbeddings($this->cluster_id, $this->search_current_embedding);

        $this->embeddings = HaiChatEmbedding::allEmbeddingsForCreateCluster($this->search_embedding, $this->selectedKnowledgeSourceIds, $this->cluster_id);

        $this->selectedKnowledgeSource = HaiChatEmbedding::queuedEmbeddings($this->selectedKnowledgeSourceIds, $this->search_connected_embedding);

        return view('livewire.admin.hai-chat.knowledge.edit-cluster');
    }

    public function deleteEmbedding($embedding_id){

        $search = array_search($embedding_id, $this->selectedKnowledgeSourceIds);

        if ($search !== false){

            unset($this->selectedKnowledgeSourceIds[$search]);
        }

    }

    public function editEmbedding($id){

        if ($id){

            $this->updateId = $id;

            $embedding = HaiChatEmbedding::whereId($id)->first();

            if ($embedding->ready_for_training === 1){

                $embedding_text = Storage::disk('local')->get('training_files/' . "retrain-embedding-" . $embedding->id . '.txt');

                $this->updateEmbeddingText = $embedding_text;

            }else{

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? "dev" : env("APP_ENV");

                $body = ["request_id" => $embedding->request_id, "loc" => $subFolder];

                $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'get_file_text', $body);

                if(isset($aiReply['text_content'])){

                    $this->updateEmbeddingText = $aiReply['text_content'];
                }
            }

//            $this->updateEmbeddingName = $embedding->name ?? null;

            $this->emit('openEditModal');
        }
    }

    public function updateEmbedding(){

        try {

            $this->validate(
                [
//                    'updateEmbeddingName' => 'required|max:50',
                    'updateEmbeddingText' => 'required|string',
                ],
                [
//                    'embedding_name.required' => 'The Name field is required.', // Corrected message to use proper text
                    'updateEmbeddingText.required' => 'The Embedding field is required.', // Corrected message to use proper text
                ]
            );

            $embedding = HaiChatEmbedding::whereId($this->updateId)->first();

            Storage::disk('local')->put('training_files/' . "retrain-embedding-" . $embedding->id . '.txt', $this->updateEmbeddingText);

            HaiChatEmbedding::updateEmbeddingTrainingFlag($this->updateId);

            session()->flash('embedding_success', "Embedding updated successfully.");

            $this->emit('closeEditEmbeddingModal');

        }catch (\Illuminate\Validation\ValidationException $exception){

            session()->flash('embedding_errors', $exception->validator->errors()->getMessages());

        } catch (\Exception $exception) {

            session()->flash('embedding_error', $exception->getMessage());
        }

        $this->emit('closeAlert');

    }

    public function reTrainFile($embedding_id){

        $embedding = HaiChatEmbedding::whereId($embedding_id)->first();

        if ($embedding){

            $embedding_text = Storage::disk('local')->get('training_files/' . "retrain-embedding-" . $embedding->id . '.txt');

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

            $body = ['loc' => $subFolder, 'request_id' => $embedding->request_id, 'text' => $embedding_text];

            GuzzleHelpers::sendRequestFromGuzzle('post', 'update_embedding', $body);

            $embedding->update(['ready_for_training' => 0]);

        }
    }

    public function selectAllEmbeddings(){

        if (count($this->embeddings) === count($this->selectedEmbeddings)){

            $this->reset('selectedEmbeddings');

        }else{

            $this->selectedEmbeddings = collect($this->embeddings)->pluck('id')->toArray();
        }
    }

    public function selectIndividualEmbedding($embedding_id){

        $search = array_search($embedding_id, $this->selectedEmbeddings);

        if ($search !== false){

            unset($this->selectedEmbeddings[$search]);

        }else{

            array_push($this->selectedEmbeddings,$embedding_id);
        }

    }

    public function addToSelectedEmbeddings(){

        $this->selectedKnowledgeSourceIds = array_merge($this->selectedEmbeddings,$this->selectedKnowledgeSourceIds);

        $this->selectedKnowledgeSource = HaiChatEmbedding::whereIn('id', $this->selectedKnowledgeSourceIds)->get();

        $this->reset('selectedEmbeddings');

    }

    public function selectConnectedEmbeddings(){

        if (count($this->selectedKnowledgeSource) === count($this->selectedConnectedEmbeddings)){

            $this->reset('selectedConnectedEmbeddings');

        }else{

            $this->selectedConnectedEmbeddings = collect($this->selectedKnowledgeSource)->pluck('id')->toArray();
        }
    }

    public function selectConnectedIndividualEmbedding($embedding_id){

        $search = array_search($embedding_id, $this->selectedConnectedEmbeddings);

        if ($search !== false){

            unset($this->selectedConnectedEmbeddings[$search]);

        }else{

            array_push($this->selectedConnectedEmbeddings,$embedding_id);
        }

    }

    public function updateCluster(){

        DB::beginTransaction();

        try {

            $this->validate();

            EmbeddingGroup::updateEmbeddingGroup($this->cluster_id,$this->name, $this->description);

            GroupEmbedding::addOrUpdateGroupIds($this->selectedKnowledgeSourceIds, $this->cluster_id);

            DB::commit();

            return redirect()->route('admin_embedding_groups')->with('Cluster created.');

        }catch (ValidationException $validationException){

            DB::rollBack();

            session()->flash('errors', $validationException->validator->errors()->getMessages());

        }catch (\Exception $exception){

            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }


    }

    public function selectAllCurrentEmbeddings(){

        if (count($this->currentEmbeddings) === count($this->selectedCurrentEmbeddings)){

            $this->reset('selectedCurrentEmbeddings');

        }else{

            $this->selectedCurrentEmbeddings = collect($this->currentEmbeddings)->pluck('embedding.id')->toArray();
        }
    }

    public function selectIndividualCurrentEmbedding($embedding_id){

        $search = array_search($embedding_id, $this->selectedCurrentEmbeddings);

        if ($search !== false){

            unset($this->selectedCurrentEmbeddings[$search]);

        }else{

            array_push($this->selectedCurrentEmbeddings,$embedding_id);
        }

    }

    public function deleteCurrentEmbedding($embedding_id){

        GroupEmbedding::removeEmbeddingsFromCluster($this->cluster_id, $embedding_id);
    }
}
