<?php

namespace App\Http\Livewire\Admin\HaiChat\Knowledge;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateCluster extends Component
{

    public $embeddings = [], $selectedEmbeddings = [], $selectedKnowledgeSourceIds = [], $selectedKnowledgeSource = [], $selectedConnectedEmbeddings = [];

    public $name, $description, $search_embedding, $bulk_option, $connected_bulk_option, $updateEmbeddingText;

    protected $rules = [
        'name' => 'required|max:50',
        'description' => 'required|max:200'
    ];

    public function updatedBulkOption(int $value){

        if ($value === 1){ // Add to Cluster

            foreach ($this->selectedEmbeddings as $embedding){

                array_push($this->selectedKnowledgeSourceIds,$embedding);

            }

            $this->reset('selectedEmbeddings');

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

            $this->reset('selectedConnectedEmbeddings');

        }

    }

    public function render()
    {

        $this->embeddings = HaiChatEmbedding::allEmbeddingsForCreateCluster($this->search_embedding, $this->selectedKnowledgeSourceIds);

        $this->selectedKnowledgeSource = HaiChatEmbedding::whereIn('id', $this->selectedKnowledgeSourceIds)->get();

        return view('livewire.admin.hai-chat.knowledge.create-cluster');
    }

    public function deleteEmbedding($embedding_id){

        $embedding = HaiChatEmbedding::singleEmbedding($embedding_id);

        $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

        $body = ['folder_n' => $embedding['request_id'], 'loc' => $subFolder];

        $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'delete_embeddings', $body);

        if ($aiReply)
        {

            GroupEmbedding::deleteGroupEmbeddings($embedding_id);

            HaiChatEmbedding::deleteEmbedding($embedding_id);

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

    public function createCluster(){

        try {

            $this->validate();

            $group = EmbeddingGroup::createEmbeddingGroup($this->name, $this->description);

            if ($group){

                GroupEmbedding::addOrUpdateGroupIds($this->selectedKnowledgeSourceIds, $group->id);
            }

            return redirect()->route('admin_embedding_groups')->with('Cluster created.');

        }catch (ValidationException $validationException){

            session()->flash('errors', $validationException->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('error', $exception->getMessage());
        }


    }
}
