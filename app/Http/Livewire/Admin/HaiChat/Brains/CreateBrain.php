<?php

namespace App\Http\Livewire\Admin\HaiChat\Brains;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\LearningCluster\LearningClusterHelpers;
use App\Models\HAIChai\BrainCluster;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateBrain extends Component
{

    public $name, $description, $search_clusters, $search_connected_clusters, $temperature = 0.5, $max_tokens = 250, $llm_model_id, $chunks = 1;

    public $llmModels = [], $groups = [], $activeGroupIds = [], $searching = false, $connectedGroups = [],

        $selectedClusters = [], $selectClustersForRemoval = [];

    protected $rules = [
        'name' => 'required|max:50|unique:chatbot,brain_name,NULL,id,deleted_at,NULL',
        'description' => 'required|max:1000',
        'temperature' => 'required',
        'max_tokens' => 'required',
        'llm_model_id' => 'required',
        'chunks' => 'required',
        'activeGroupIds' => 'required|array',
    ];

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

    public function addToCluster($group_id){

        if (!array_search($group_id, $this->activeGroupIds)){

            array_push($this->activeGroupIds, $group_id);
        }

        $this->connectedGroups = EmbeddingGroup::whereIn('id', $this->activeGroupIds)->get();
    }

    public function removeFromCluster($group_id){

        $search = array_search($group_id, $this->activeGroupIds);

        if ($search >= 0){

            unset($this->activeGroupIds[$search]);

        }

        $this->connectedGroups = EmbeddingGroup::whereIn('id', $this->activeGroupIds)->get();

    }

    public function updatedSearchClusters($value){

        $this->searching = true;

        $this->groups = EmbeddingGroup::where('name', "LIKE", "%$value%")->whereNotIn('id', $this->activeGroupIds)->get();
    }

    public function updatedSearchConnectedClusters($value){

        $this->searching = true;

        $this->connectedGroups = EmbeddingGroup::where('name', "LIKE", "%$value%")->whereIn('id', $this->activeGroupIds)->get();
    }

    public function createBrain(){

        DB::beginTransaction();

        try {

            $this->validate();

            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

            $body = ['vendor_n' => $this->name, 'loc' => $subFolder];

            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'create-chatbot', $body);

            if ($aiReply){

                $chatBot = Chatbot::createChatBot($aiReply, $this->description, $this->name);

                LearningClusterHelpers::addContentToLearningCluster($this->name);

                if (count($this->activeGroupIds) > 0){

//                    GroupEmbedding::connectAllGroupEmbeddings($this->activeGroupIds, $aiReply);

                    BrainCluster::addClustersWithBrain($this->activeGroupIds, $chatBot['id']);

                }

                if ($chatBot){

                    HaiChatSetting::updateHaiChatSetting($this->temperature, $this->max_tokens, $this->chunks, $this->llm_model_id,$chatBot->id);

                }

                DB::commit();

                $this->reset();

                session()->flash('success','Brain created successfully');

                return redirect()->route('admin_hai_chat');

            }else{

                DB::rollBack();

                session()->flash('error', "Something went wrong while creating chat-bot on S3.");
            }

        }catch (ValidationException $exception){

            DB::rollBack();

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }

    }

    public function selectCluster($group_id){

        if (!array_search($group_id, $this->selectedClusters)){

            array_push($this->selectedClusters, $group_id);
        }

    }

    public function addAllClustersToActiveClusters(){

        $this->activeGroupIds = array_merge($this->selectedClusters, $this->activeGroupIds);

        $this->connectedGroups = EmbeddingGroup::whereIn('id', $this->activeGroupIds)->get();

    }

    public function selectClusterForRemove($group_id){

        if (!array_search($group_id, $this->selectClustersForRemoval)){

            array_push($this->selectClustersForRemoval, $group_id);
        }

    }

    public function removeAllSelectedClusters(){

        foreach ($this->selectClustersForRemoval as $group_id){

            $search = array_search($group_id, $this->activeGroupIds);

            if ($search >= 0){

                unset($this->activeGroupIds[$search]);
            }

        }

        $this->connectedGroups = EmbeddingGroup::whereIn('id', $this->activeGroupIds)->get();

    }

    public function render()
    {

        $this->llmModels = LlmModel::all();

        if ($this->searching){

            $this->searching = false;

        }else{

            $this->groups = EmbeddingGroup::all();
        }


        return view('livewire.admin.hai-chat.brains.create-brain');
    }
}
