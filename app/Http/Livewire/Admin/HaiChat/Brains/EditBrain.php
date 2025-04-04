<?php

namespace App\Http\Livewire\Admin\HaiChat\Brains;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditBrain extends Component
{

    public $chat_bot_id, $name, $description, $search_clusters, $search_connected_clusters, $temperature, $max_tokens, $llm_model_id, $chunks;

    public $llmModels = [], $groups = [], $activeGroupIds = [], $searching = false, $connectedGroups = [],
        $selectedClusters = [], $selectClustersForRemoval = [];

    protected $rules = [
//        'name' => 'required|max:30',
        'description' => 'required|max:1000',
        'temperature' => 'required',
        'max_tokens' => 'required',
        'llm_model_id' => 'required',
        'chunks' => 'required',
    ];

    public function addToCluster($group_id){

        GroupEmbedding::connectGroupEmbeddings($group_id, $this->name);
    }

    public function removeFromCluster($group_id){

        GroupEmbedding::removeGroupEmbeddings($group_id, $this->name);

    }

    public function updatedSearchClusters($value){

        $this->searching = true;

        $this->groups = EmbeddingGroup::nonActiveGroups($this->name, $value);
    }

    public function updatedSearchConnectedClusters($value){

        $this->searching = true;

        $this->connectedGroups = EmbeddingGroup::activeGroups($this->name, $value);
    }

    public function updateBrain(){

        DB::beginTransaction();

        try {

            $this->validate();

//            $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");
//
//            $body = ['vendor_n' => $this->name, 'loc' => $subFolder];
//
//            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'create-chatbot', $body);

//            if ($aiReply){

                Chatbot::updateChatBot($this->chat_bot_id, $this->description);

                HaiChatSetting::updateHaiChatSetting($this->temperature, $this->max_tokens, $this->chunks, $this->llm_model_id,$this->chat_bot_id);

                DB::commit();

                session()->flash('success','Brain updated');

                return redirect()->route('admin_hai_chat');

//            }else{
//
//                DB::rollBack();
//
//                session()->flash('error', "Something went wrong while creating chat-bot on S3.");
//            }

        }catch (ValidationException $exception){

            DB::rollBack();

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }

    }

    public function chatBotDetail(){

        $chatBotDetail = Chatbot::whereId($this->chat_bot_id)->first();

        if($chatBotDetail){

            $this->name = $chatBotDetail['name'];
            $this->description = $chatBotDetail['description'];

            $settings = HaiChatSetting::where('chat_bot_id', $this->chat_bot_id)->first();

            if ($settings){
                $this->temperature = $settings['temperature'];
                $this->max_tokens = $settings['max_token'];
                $this->chunks = $settings['chunk'];
                $this->llm_model_id = $settings['model_type'];

            }

        }

    }

    public function selectCluster($group_id){

        if (!array_search($group_id, $this->selectedClusters)){

            array_push($this->selectedClusters, $group_id);
        }

    }

    public function addAllClustersToActiveClusters(){

        GroupEmbedding::connectAllGroupEmbeddings($this->selectedClusters, $this->name);

//        $this->connectedGroups = EmbeddingGroup::whereIn('id', $this->activeGroupIds)->get();

    }

    public function selectClusterForRemove($group_id){

        if (!array_search($group_id, $this->selectClustersForRemoval)){

            array_push($this->selectClustersForRemoval, $group_id);
        }

    }

    public function removeAllSelectedClusters(){

        GroupEmbedding::connectAllGroupEmbeddings($this->selectedClusters, $this->name);

//        foreach ($this->selectClustersForRemoval as $group_id){
//
//            $search = array_search($group_id, $this->activeGroupIds);
//
//            if ($search >= 0){
//
//                unset($this->activeGroupIds[$search]);
//            }
//
//        }

        $this->connectedGroups = EmbeddingGroup::whereIn('id', $this->activeGroupIds)->get();

    }

    public function render()
    {

        $this->chatBotDetail();

        $this->llmModels = LlmModel::all();

        if ($this->searching){

            $this->searching = false;

        }else{

            $this->groups = EmbeddingGroup::nonActiveGroups($this->name);

            $this->connectedGroups = EmbeddingGroup::activeGroups($this->name);
        }

        return view('livewire.admin.hai-chat.brains.edit-brain');
    }
}
