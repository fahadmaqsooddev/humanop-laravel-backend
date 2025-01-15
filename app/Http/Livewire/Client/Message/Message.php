<?php

namespace App\Http\Livewire\Client\Message;

use App\Events\messages\MessageSent;
use App\Events\messages\NewMessage;
use App\Helpers\Helpers;
use App\Models\Client\Connection\Connection;
use App\Models\Client\Follow\Follow;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Message extends Component
{
    public $chats = [], $message, $chat_user_id, $messages = [], $logged_in_user_id, $chat_user, $connections = [],

    $filter_text, $redirected_user_id;

    protected $listeners = ['updateChatUserId'];

    public function updatingFilterText($value){

        $this->chats = MessageThread::chats($value);

        $this->filter_text = $value;
    }

    public function render()
    {

        $this->chats = MessageThread::chats($this->filter_text);

        $this->logged_in_user_id = Session::get('logged_in_user_id', function (){

            return Helpers::getWebUser()->id;

        });

        if ($this->redirected_user_id){

            $chat_id = MessageThread::where(function ($query){

                $query->where('sender_id', $this->redirected_user_id)

                    ->where('receiver_id', $this->logged_in_user_id);

            })->orWhere(function ($query){

                $query->where('receiver_id', $this->redirected_user_id)

                    ->where('sender_id', $this->logged_in_user_id);

            })->first()->id ?? null;

            $this->chat_user = User::whereId($this->redirected_user_id)->select(['id','first_name','last_name'])->first();

            $this->messages = \App\Models\Client\Message\Message::threadMessages($chat_id);

            $this->redirected_user_id = null;
        }

        $this->connections = Connection::userConnections();

        $this->emit('scrollToBottom');

        return view('livewire.client.message.message');
    }

    public function sendMessage(){

        $connection_exists = Connection::connectionExists($this->chat_user_id);

        if ($connection_exists){

            $thread = MessageThread::createOrGetMessageThread($this->chat_user_id);

            $data['message_thread_id'] = $thread->id ?? null;

            $data['receiver_id'] = $this->chat_user_id;

            $data['message'] = $this->message;

            $data['sender_id'] = Helpers::getWebUser()->id;

            $senderUserName = Helpers::getWebUser()['first_name'] . ' ' . Helpers::getWebUser()['last_name'];

            if (strlen(trim($this->message)) > 0){

               $createMessage =  \App\Models\Client\Message\Message::createMessage($data);

               $heading = $senderUserName . "send you a message";

                event(new MessageSent($data['receiver_id'], $createMessage['message'], $createMessage['created_at'], $heading));
                event(new NewMessage(Helpers::getWebUser()->id,$data['receiver_id'],Helpers::getWebUser(),$data['message'],$createMessage['created_at']));
                
            }

            $this->reset('message');

            $this->messages = \App\Models\Client\Message\Message::threadMessages($thread->id);

            $this->chats = MessageThread::chats();

        }else{

            toastr()->error("Connect first to send message", '');
        }

    }

    public function messages($chat_id = null, $user = null){

        $this->chat_user_id = $user['id'];

        $this->chat_user = $user;

        if ($chat_id === null || empty($chat_id)){

            $thread = MessageThread::createOrGetMessageThread($user['id']);

            $chat_id = $thread->id;
        }

        $this->messages = \App\Models\Client\Message\Message::threadMessages($chat_id);

    }

    public function deleteChat($user_id){

        MessageThread::deleteMessageThread($user_id);

        $this->chats = MessageThread::chats();

        $this->messages = [];

        $this->chat_user = '';

    }

    public function updateChatUserId($user_id){

        $this->chat_user_id = $user_id;
    }
}
