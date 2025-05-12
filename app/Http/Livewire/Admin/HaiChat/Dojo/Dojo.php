<?php

namespace App\Http\Livewire\Admin\HaiChat\Dojo;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Dojo extends Component
{

    public $training_session_name, $session_id, $message;

    public $allSessions = [], $conversations = [];

    protected $rules = [
        'message' => 'required',
        'session_id' => 'required',
    ];

    protected $messages = [
        'message' => 'Message is required',
        'session_id' => 'Select or Create new session'
    ];

    public function render()
    {
        $this->allSessions();

        if ($this->session_id){

            $this->loadAllConversation();

            $this->emit('scrollToBottom');
        }

        return view('livewire.admin.hai-chat.dojo.dojo');
    }

    public function openNewTrainingSessionModal(){

        $this->emit('openNewTrainingSessionModal');

    }

    public function createNewTrainingSession(){

        $this->validate([
            'training_session_name' => 'required|max:50',
        ]);

        $body = ['title' => $this->training_session_name];

        $reply = GuzzleHelpers::sendRequestFromGuzzleForDojo('post','conversations',$body);

        if (isset($reply['id'])){

            $this->reset('training_session_name');

            $this->session_id = $reply['id'];

            session()->flash('training_success', 'Session created');

        }elseif ($reply['detail']){

            session()->flash('training_error', $reply['detail']);

        }else{

            session()->flash('training_error', "Something went wrong.");
        }

    }

    public function allSessions(){

        $reply = GuzzleHelpers::sendRequestFromGuzzleForDojo('get','conversations');

        $this->allSessions = $reply;

    }

    public function deleteSession(){

        GuzzleHelpers::sendRequestFromGuzzleForDojo('delete',"conversations/$this->session_id");
    }

    public function selectSession($id){

        $this->session_id = $id;
    }

    public function sendMessage(){

        try {

            $this->validate();

            $body = ['content' => $this->message];

            $reply = GuzzleHelpers::sendRequestFromGuzzleForDojo('post', "conversations/$this->session_id/messages", $body);

            if (isset($reply['content'])){

                $this->reset('message');

            }elseif (isset($reply['detail'])){

                session()->flash('error', $reply['detail']);
            }

        }catch (ValidationException $validationException){

            session()->flash('errors', $validationException->validator->errors()->getMessages());

        }catch (\Exception $exception){

            session()->flash('error', $exception->getMessage());
        }

    }

    public function loadAllConversation(){

        $reply = GuzzleHelpers::sendRequestFromGuzzleForDojo('get', "conversations/$this->session_id");

        if (isset($reply['messages'])){

            $this->conversations = $reply['messages'];
        }

    }

    public function endTrainingSession()
    {

        $body = ['status' => 'ended'];

        GuzzleHelpers::sendRequestFromGuzzleForDojo('put', "conversations/$this->session_id/status", $body);

        $this->reset('session_id','conversations');
    }

    public function exportSessionConversation(){

        $body = ['status' => 'ended'];

        $reply = GuzzleHelpers::sendRequestFromGuzzleForDojoExport('get', "conversations/$this->session_id/export/jsonl", $body);

        Storage::disk('local')->put('/training-session-files/conversation.jsonl',$reply);

        return redirect()->route('admin_export_conversations');

    }

    public function trainMoreSession()
    {

        $body = ['status' => 'active'];

        GuzzleHelpers::sendRequestFromGuzzleForDojo('put', "conversations/$this->session_id/status", $body);
    }


}
