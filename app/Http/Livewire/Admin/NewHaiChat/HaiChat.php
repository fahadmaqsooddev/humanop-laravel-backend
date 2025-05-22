<?php

namespace App\Http\Livewire\Admin\NewHaiChat;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\LearningCluster\LearningClusterHelpers;
use App\Models\HAIChai\BrainCluster;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class HaiChat extends Component
{
    public $chats, $name, $description, $chatBot, $copyChatBotId, $search_brain;
    protected $listeners = ['deleteChatbot'];
    protected $rules = [
        'name' => 'required|max:30|unique:chatbot,brain_name,NULL,id,deleted_at,NULL',
        'description' => 'required|max:2000',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'description.required' => 'Information is required',
    ];

    public function updatedSearchBrain($value){

        $this->search_brain = $value;
    }

    public function deleteChatbot($id)
    {
        $chat = Chatbot::singleChat($id);

        LearningClusterHelpers::deleteLearningClusterFile($chat->name);

        $chat->delete();

        session()->flash('success', "Chatbot deleted successfully.");

        $this->emit('closeAlert');

    }

    public function resetForm()
    {
        $this->reset(['name', 'description']);
    }

    public function showModalChatBotDetail($id){

        $this->chatBot = Chatbot::singleChat($id);
    }

    public function closeChatBotDetailModal(){

        $this->chatBot = null;
    }

    public function copyChatBot($id){

        $this->reset('name','description');

        $this->copyChatBotId = $id;
    }

    public function createDuplicateChatBot(){

        try {

            $this->validate();

            $chatBot = Chatbot::singleChat($this->copyChatBotId);

            if ($chatBot){

                $newChatBot = Chatbot::createNewChatBot($this->name, $this->description ?? $chatBot->description, $chatBot['max_tokens'] ,$chatBot['temperature'],$chatBot['chunks'], $chatBot['model_type']);

                ChatPrompt::duplicatingNewChatBot($chatBot->id, $newChatBot->id);

                BrainCluster::addDuplicateBrainClusters($chatBot->id,$newChatBot->id);

                $this->reset('copyChatBotId','name','description');

                $this->emit('closeCopyChatBot');

            }

            session()->flash('success','Chatbot copied');

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function redirectToCreateBrainInterface(){

        try {

            $this->validate();

            return redirect()->route('admin_create_brain')->with(['name' => $this->name, 'description' => $this->description]);

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function render()
    {
        $this->chats = Chatbot::chatBots($this->search_brain);

        return view('livewire.admin.new-hai-chat.hai-chat', ['chats' => $this->chats]);
    }
}
