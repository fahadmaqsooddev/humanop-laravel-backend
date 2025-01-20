<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Helpers\HaiChat\HaiChatHelpers;
use App\Helpers\Helpers;
use App\Models\HAIChai\EmbeddingSetting;
use App\Models\HAIChai\HaiChaiChunk;
use App\Models\HAIChai\HaiChatEmbedding;
use App\Models\KnowledgeBase\KnowledgeBase;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class SearchEmbedding extends Component
{
    public $name, $query, $chunks = [];

    public $embedding;

    protected $rules = [
        'query' => 'required',
    ];

    protected $messages = [
        'query.required' => 'Query is required',
    ];
    public function submitForm()
    {
        try {

//            $client = \OpenAI::client(env('OPEN_AI_API_KEY'));
//
//            $response = $client->embeddings()->create([
//                'model' => 'text-embedding-3-small',
//                'input' => $this->query,
//            ]);
//
//            $response = $response->toArray();
//
//            $pinecone = new \Probots\Pinecone\Client(env('PINECONE_API_KEY'));
//
//            $pinecone->setIndexHost('https://my-index-wgj0px8.svc.aped-4627-b74a.pinecone.io');

//            foreach ($response['data'] as $embedding){
//
//                $response = $pinecone->data()->vectors()->query(
//                    vector:$embedding['embedding'],
//                    topK : $this->embedding->chunks ?? 1,
//                    includeMetadata : true,
//                    filter : [
////                        'id' => ['$in' => ['vector_1','vector_2']]
////                        'id' => ['$in' => ['vector_1']],
//                    ]
//                );
//
//                $result = $response->array();
//
//                $this->chunks = array_filter($result['matches'] ?? [], function ($match) {
//                    return $match['score'] >= 0.3;
//                });
//
//            }


            $knowledgeBase = KnowledgeBase::where('embedding_id', $this->embedding->id)->get();

            $relevantChunks = HaiChatHelpers::findRelevantChunks($this->query, $knowledgeBase, $this->embedding->chunks ?? 1);

            $this->chunks = $relevantChunks;

            if (count($this->chunks) === 0){

                session()->flash('error', 'No result found');

            }

            $this->query = '';

        }catch (\Exception $exception)
        {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function render()
    {
        return view('livewire.admin.hai-chat.search-embedding');
    }
}
