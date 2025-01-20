<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Helpers\Helpers;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use App\Models\KnowledgeBase\KnowledgeBase;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class Embedding extends Component
{
    public $name,$embedding, $group_id, $group_ids = [], $fileInputId, $groups, $embedding_name;

    use WithFileUploads;

    protected $listeners = ['deleteEmbedding'];

    public function deleteEmbedding($id)
    {

//        $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/delete_embeddings', ['folder_n' => $embedding['request_id']]);
//
//        if ($embedding)
//        {

        GroupEmbedding::deleteGroupEmbeddings($id);

        KnowledgeBase::deleteEmbedding($id);

        HaiChatEmbedding::deleteEmbedding($id);


        session()->flash('success', "Embedding deleted successfully.");

//        }

    }

    public function getEmbeddings()
    {
        $this->embeddings = GroupEmbedding::groupEmbeddings($this->group_id);

    }

    public function createEmbedding(){
        try {

            $this->validate(
                [
                    'embedding_name' => 'required|max:50',
                    'embedding' => 'required|file|mimes:txt,pdf', // Corrected 'memes' to 'mimes'
                ],
                [
                    'embedding_name.required' => 'The Name field is required.', // Corrected message to use proper text
                    'embedding.required' => 'The Embedding field is required.', // Corrected message to use proper text
                    'embedding.mimes' => 'The Embedding must be a file of type: txt, pdf.', // Added message for mime type validation
                ]
            );

            $embedding = HaiChatEmbedding::createEmbedding($this->embedding_name);

            $texts = Helpers::stringFromPdfOrTextFile($this->embedding);

            $yourApiKey = env('OPEN_AI_API_KEY');

            $client = \OpenAI::client($yourApiKey);

            foreach ($texts as $text){

                $response = $client->embeddings()->create([
                    'model' => 'text-embedding-3-small',
                    'input' => $text,
                ]);

                $response = $response->toArray();

                foreach ($response['data'] as $embeddingVector){

                    KnowledgeBase::createEmbeddingKnowledge($text,$embeddingVector,$embedding->id);

                    if($embedding){

                        GroupEmbedding::addOrUpdateEmbeddingIds([$this->group_id], $embedding->id);

                        session()->flash('embedding_success', "Embedding created successfully.");

                        $this->emit('closeCreateEmbeddingModal');

                        $this->reset('embedding_name','group_ids');

                        $this->fileInputId++; // this is just for remove placeholder for file input field

                        $this->embedding = null;

                    }else{

                        DB::rollBack();

                        session()->flash('embedding_error', "Something went wrong.");
                    }

                    DB::commit();

                }

            }

        }catch (\Illuminate\Validation\ValidationException $exception){

            session()->flash('embedding_errors', $exception->validator->errors()->getMessages());

        } catch (\Exception $exception) {

            session()->flash('embedding_error', $exception->getMessage());
        }

        $this->emit('closeAlert');
    }

    public function render()
    {
        $this->getEmbeddings();

        return view('livewire.admin.hai-chat.embedding');
    }
}
