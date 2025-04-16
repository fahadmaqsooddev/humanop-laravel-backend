<?php

namespace App\Http\Livewire\Admin\HaiChat\Knowledge;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use ZipArchive;

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

        return view('livewire.admin.hai-chat.knowledge.universal-embedding');
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

        foreach ($this->selectedEmbeddings as $key => $embeddingId){

            $embedding = HaiChatEmbedding::whereId($embeddingId)->first();

            if ($embedding){

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? "dev" : env("APP_ENV");

                $body = ["request_id" => $embedding['request_id'], "loc" => $subFolder];

                $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'get_file_text', $body);

                if(isset($aiReply['text_content'])){

                    Storage::disk('local')->put('export-files/' . $embedding['request_id'] . '.txt', $aiReply['text_content']);

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
