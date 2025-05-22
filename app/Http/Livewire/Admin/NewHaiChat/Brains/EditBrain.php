<?php

namespace App\Http\Livewire\Admin\NewHaiChat\Brains;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\BrainCluster;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use App\Models\HAIChai\PublishedChatBot;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use function Symfony\Component\String\s;

class EditBrain extends Component
{

    public $chat_bot_id, $name, $description, $search_clusters, $search_connected_clusters, $temperature, $max_tokens, $llm_model_id, $chunks, $is_published = 0;

    public $llmModels = [], $groups = [], $activeGroupIds = [], $searching = false, $connectedGroups = [],
        $selectedClusters = [], $selectClustersForRemoval = [];

    public function rules(){

        return [
            'name' => 'required|max:50|unique:chatbot,name,'. $this->chat_bot_id .',id,deleted_at,NULL',
            'description' => 'required|max:1000',
            'temperature' => 'required',
            'max_tokens' => 'required',
            'llm_model_id' => 'required',
            'chunks' => 'required'
        ];
    }

    protected $messages = [
        'name.required' => 'Brain name is required',
        'name.unique' => 'Brain with this name already exists.',
        'description.required' => 'Brain description is required',
        'temperature.required' => 'Temperature are required',
        'max_token.required' => 'Max tokens are required',
        'llm_model_id.required' => 'Select a LLM Model',
        'chunks.required' => 'Chunks are required',
        'activeGroupIds.required' => 'Attach at-least one cluster',

    ];

    protected function getMessages()
    {
        return [
            'name.required' => 'Brain name is required.',
            'name.unique' => 'Brain name already exists. Try another one.',
        ];
    }

    public function addToCluster($cluster_id){

        BrainCluster::addClusterWithBrain($cluster_id, $this->chat_bot_id);

    }

    public function removeFromCluster($cluster_id){

        BrainCluster::removeClusterFromBrain($cluster_id, $this->chat_bot_id);

    }

    public function updatedSearchClusters($value){

        $this->searching = true;

        $this->groups = EmbeddingGroup::nonActiveGroups($this->chat_bot_id, $value);
    }

    public function updatedSearchConnectedClusters($value){

        $this->searching = true;

        $this->connectedGroups = EmbeddingGroup::activeGroups($this->chat_bot_id, $value);
    }

    public function updatedTemperature($value){

        $this->searching = true;

        $this->temperature = $value;
    }

    public function updateBrain(){

        try {

            $this->validate();

            Chatbot::updateNewChatBot($this->chat_bot_id, $this->name, $this->description, $this->max_tokens, $this->temperature, $this->chunks, $this->llm_model_id);

            session()->flash('success','Brain updated');

            return redirect()->route('admin_hai_chat');

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('error', $exception->getMessage());
        }

    }

    public function chatBotDetail(){

        $chatBotDetail = Chatbot::whereId($this->chat_bot_id)->first();

        if($chatBotDetail){

            $this->name = $chatBotDetail['name'];
            $this->description = $chatBotDetail['description'];
            $this->is_published = $chatBotDetail['is_connected'];
            $this->temperature = $chatBotDetail['temperature'];
            $this->max_tokens = $chatBotDetail['max_tokens'];
            $this->chunks = $chatBotDetail['chunks'];
            $this->llm_model_id = $chatBotDetail['model_type'];

        }

    }

    public function selectCluster($group_id){

        if (!array_search($group_id, $this->selectedClusters)){

            array_push($this->selectedClusters, $group_id);
        }

    }

    public function addAllClustersToActiveClusters(){

        BrainCluster::addClustersWithBrain($this->selectedClusters, $this->chat_bot_id);
    }

    public function selectClusterForRemove($group_id){

        if (!array_search($group_id, $this->selectClustersForRemoval)){

            array_push($this->selectClustersForRemoval, $group_id);
        }
    }

    public function removeAllSelectedClusters(){

        BrainCluster::removeClustersFromBrain($this->selectClustersForRemoval, $this->chat_bot_id);

        $this->connectedGroups = EmbeddingGroup::whereIn('id', $this->activeGroupIds)->get();

    }

    public function publishChatBot(){

        $active_embedding_ids = BrainCluster::connectedNewClusterEmbeddingIds($this->chat_bot_id);

        $chat_bot = Chatbot::whereId($this->chat_bot_id)

            ->with(['persona','restrictedKeywords:chatbot_id,word,message'])

            ->first();

        if ($chat_bot){

            $data = [
                'temperature' => $chat_bot['temperature'],
                'max_tokens' => $chat_bot['max_tokens'],
                'embedding_ids' => $active_embedding_ids,
                'name' => $chat_bot['name'],
                'chunks' => $chat_bot['chunks'],
                'model_type' => $chat_bot['model_type'],
                'description' => $chat_bot['description'],
                'prompt' => $chat_bot->persona?->prompt,
                'restriction' => $chat_bot->persona?->restriction,
                'persona_name' => $chat_bot->persona?->persona_name,
                'chat_bot_id' => $chat_bot['id'],
                'restricted_keywords' => $chat_bot['restrictedKeywords']->toArray(),
                'is_connected' => 1,
            ];

            PublishedChatBot::addPublishedChatBot($data);

            $chat_bot->update(['is_connected' => 1]);

            Chatbot::whereNot('id', $chat_bot->id)->update(['is_connected' => 0]);

            session()->flash('success', 'Chatbot published');

        }

    }

    public function render()
    {

        BrainCluster::connectedClusterEmbeddingIds($this->chat_bot_id);

        if ($this->searching){

            $this->searching = false;

        }else{

            $this->chatBotDetail();

            $this->groups = EmbeddingGroup::nonActiveGroups($this->chat_bot_id);

            $this->connectedGroups = EmbeddingGroup::activeGroups($this->chat_bot_id);

            $this->llmModels = LlmModel::all();
        }

        return view('livewire.admin.new-hai-chat.brains.edit-brain');
    }
}
