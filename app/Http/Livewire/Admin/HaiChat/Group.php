<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Helpers\Helpers;
use App\Models\HAIChai\EmbeddingGroup;
use App\Models\HAIChai\GroupEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use App\Models\KnowledgeBase\KnowledgeBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class Group extends Component
{
    use WithFileUploads;

    protected $listeners = ['deleteEmbedding'];

    public $groups, $name, $embedding_ids = [], $embedding_name, $embedding, $embeddings, $embedding_id,

        $group_ids = [], $fileInputId, $showGroupDropdownMenu = false, $group_search, $dropDownGroups = [],

        $is_group = true, $showEmbDropdownMenu = false, $dropDownEmbeddings = [], $embedding_search,

        $selectedGroups = [], $selectedEmbeddings = [];

    protected $rules = [
        'embedding_name' => 'required|max:50',
        'embedding' => 'required|file|mimes:txt,pdf', // Corrected 'memes' to 'mimes'
        'group_ids' => 'required|array',
        'group_ids.*' => 'required|exists:embedding_groups,id',
    ];

    protected $messages = [
        'embedding_name.required' => 'The Name field is required.', // Corrected message to use proper text
        'embedding.required' => 'The Embedding field is required.', // Corrected message to use proper text
        'embedding.mimes' => 'The Embedding must be a file of type: txt, pdf.', // Added message for mime type validation
        'group_ids.required' => 'Group id are required',
    ];

    public function render()
    {
        $this->groups = EmbeddingGroup::allGroups();

        $this->dropDownGroups = EmbeddingGroup::groupsForDropDown($this->group_search);

        $this->dropDownEmbeddings = HaiChatEmbedding::allEmbeddingsForDropDown($this->embedding_search);

        $this->embeddings = HaiChatEmbedding::allEmbeddings();

        return view('livewire.admin.hai-chat.group');
    }

    public function createEmbedding(){

        DB::beginTransaction();

        try {

            $this->validate();

            $texts = Helpers::stringFromPdfOrTextFile($this->embedding);

            $yourApiKey = env('OPEN_AI_API_KEY');

            $client = \OpenAI::client($yourApiKey);

            $response = $client->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $texts,
            ]);

            $response = $response->toArray();

            foreach ($response['data'] as $embeddingVector){

                $embedding = HaiChatEmbedding::createEmbedding($this->embedding_name);

                KnowledgeBase::createEmbeddingKnowledge($texts, $embeddingVector, $embedding->id);

                if($embedding){

                    GroupEmbedding::addOrUpdateEmbeddingIds($this->group_ids, $embedding->id);

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

        }catch (\Illuminate\Validation\ValidationException $exception){

            DB::rollBack();

            session()->flash('embedding_errors', $exception->validator->errors()->getMessages());

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('embedding_error', $exception->getMessage());

        }

        $this->showGroupDropdownMenu = false;

        $this->emit('closeAlert');
    }

    public function createGroup(){

        try {

            $this->validate([

                'name' => 'required|max:20',
                'embedding_ids' => 'required|array',
                'embedding_ids.*' => 'required|exists:embeddings,id',

            ], [
                    'name.required' => 'Group name is required'
                ]
            );

            $group = EmbeddingGroup::createEmbeddingGroup($this->name);

            if (count($this->embedding_ids) > 0){

                GroupEmbedding::addOrUpdateGroupIds($this->embedding_ids, $group->id);
            }

            session()->flash('success', "Group created successfully.");

            $this->emit('closeCreateGroupModal');

            $this->reset('name','embedding_ids');

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('success', $exception->getMessage());
        }

        $this->showEmbDropdownMenu = false;

        $this->emit('closeAlert');
    }

    public function deleteEmbedding($id)
    {
//        $embedding = HaiChatEmbedding::singleEmbedding($id);

//        if ($embedding){

        GroupEmbedding::deleteGroupEmbeddings($id);

        KnowledgeBase::deleteEmbedding($id);

        HaiChatEmbedding::deleteEmbedding($id);

        session()->flash('embedding_deleted', "Embedding deleted successfully.");

        $this->emit('closeAlert');

//        }else{
//
//            session()->flash('error', "Something went wrong while deleting {$embedding['name']} embedding.");
//
//            $this->emit('closeAlert');
//        }

    }

    public function setEmbeddingId($embedding_id){

        $this->group_ids = GroupEmbedding::embeddingGroups($embedding_id);

        $this->selectedGroups = EmbeddingGroup::groupNames($this->group_ids);

        $this->embedding_id = $embedding_id;
    }

    public function addEmbeddingToGroups(){

        try {

            $this->validate(['group_ids' => 'required']);

            GroupEmbedding::addOrUpdateEmbeddingIds($this->group_ids, $this->embedding_id);

            session()->flash('embedding_group_success', 'Embedding are added into groups');

            $this->emit('closeAlert');

            $this->group_ids = GroupEmbedding::embeddingGroups($this->embedding_id);

            $this->emit('closeAddGroupToEmbeddingModal');

            $this->showGroupDropdownMenu = false;

        }catch (ValidationException $validationException){

            session()->flash('embedding_group_errors', $validationException->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('embedding_group_error', $exception->getMessage());
        }
    }

    public function addGroupIds(int $id, $name = null){

        if(in_array($id,$this->group_ids)){

            $this->group_ids = array_diff($this->group_ids,[$id]);

            $this->selectedGroups = array_diff($this->selectedGroups, [$name]);

        }else{

            array_push($this->group_ids,$id);

            array_push($this->selectedGroups, $name);
        }

        $this->showGroupDropdownMenu = true;

    }

    public function updatedGroupSearch($value){

        $this->group_search = $value;

        $this->showGroupDropdownMenu = true;

    }

    public function changeIsGroup($value){

        $this->is_group = $value;

        $this->selectedGroups = [];

        $this->group_ids = [];
    }

    public function addEmbeddingIds(int $id, $name = null){

        if(in_array($id,$this->embedding_ids)){

            $this->embedding_ids = array_diff($this->embedding_ids,[$id]);

            $this->selectedEmbeddings = array_diff($this->selectedEmbeddings, [$name]);

        }else{

            array_push($this->embedding_ids,$id);

            array_push($this->selectedEmbeddings, $name);
        }

        $this->showEmbDropdownMenu = true;

    }

    public function updatedEmbeddingSearch($value){

        $this->embedding_search = $value;

        $this->showEmbDropdownMenu = true;

    }
}
