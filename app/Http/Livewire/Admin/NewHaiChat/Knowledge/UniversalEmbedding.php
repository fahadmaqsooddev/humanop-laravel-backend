<?php

namespace App\Http\Livewire\Admin\NewHaiChat\Knowledge;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class UniversalEmbedding extends Component
{

    protected $listeners = ['deleteEmbedding', '$refresh'];

    public $embeddings = [], $clusters = [], $selectedEmbeddings = [];

    public $search_embedding, $cluster_id, $updateId, $updateEmbeddingName, $updateEmbeddingText, $bulk_option;

    public function updatedBulkOption(int $value){

        if ($value === 1){ // Retrain

            $this->retrainBulkEmbedding();

        }elseif ($value === 2){ // Export

            $this->exportAllSelectedFiles();

        }elseif ($value === 3){ // Delete

            $this->deleteBulkEmbeddings();
        }
    }

    public function render()
    {

        $this->embeddings = HaiChatEmbedding::allUniversalEmbeddings($this->search_embedding, $this->cluster_id);

        $this->clusters = EmbeddingGroup::all();

        return view('livewire.admin.new-hai-chat.knowledge.universal-embedding');
    }

    public function deleteEmbedding($embedding_id){

        $embedding = HaiChatEmbedding::singleEmbedding($embedding_id);

        $response = GuzzleHelpers::sendRequestFromGuzzleForNewHai('delete', "knowledge/documents/$embedding->request_id");

        if (isset($response['message']))
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

                $response = GuzzleHelpers::sendRequestFromGuzzleForNewHai('get', "knowledge/documents/$embedding->request_id");

                if(isset($response['full_text'])){

                    $this->updateEmbeddingText = $response['full_text'];

                }else{

                    $this->updateEmbeddingText = "";
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

            $body = ['content' => $embedding_text];

            GuzzleHelpers::sendRequestFromGuzzleForNewHai('put', "knowledge/documents/$embedding->request_id/update", $body);

            $embedding->update(['ready_for_training' => 0]);

        }
    }

    public function selectAllEmbeddings(){

        if (count($this->selectedEmbeddings) === count($this->embeddings)){

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

    public function retrainBulkEmbedding(){

        $embeddings = HaiChatEmbedding::whereIn('id',$this->selectedEmbeddings)->where('ready_for_training', 1)->get();

        foreach ($embeddings as $embedding){

            $this->reTrainFile($embedding['id']);
        }

        $this->reset('selectedEmbeddings','bulk_option');

    }

    public function deleteBulkEmbeddings(){

        $embeddings = HaiChatEmbedding::whereIn('id',$this->selectedEmbeddings)->get();

        foreach ($embeddings as $embedding){

            $this->deleteEmbedding($embedding['id']);
        }

        $this->reset('selectedEmbeddings', 'bulk_option');

    }

    public function exportAllSelectedFiles(){

        $files = [];

        foreach ($this->selectedEmbeddings as $embeddingId){

            $embedding = HaiChatEmbedding::whereId($embeddingId)->first();

            if ($embedding){

                $response = GuzzleHelpers::sendRequestFromGuzzle('get', "knowledge/documents/$embedding->request_id");

                if(isset($response['id'])){

                    Storage::disk('local')->put('export-files/' . $embedding['request_id'] . '.txt', $response['full_text']);

                    array_push($files,storage_path('app/export-files/' . $embedding['request_id'] . '.txt'));
                }

            }

        }

        $zip = new \ZipArchive();

        $zipPath = storage_path('knowledge.zip');

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {

            foreach ($files as $file) {

                if (file_exists($file)) {

                    $zip->addFile($file, basename($file)); // second param = file name inside the zip
                }
            }

            $zip->close();

            Storage::disk('local')->deleteDirectory('export-files');

            $this->reset('selectedEmbeddings','bulk_option');

            return redirect()->route('download-zip');
        }

    }
}
