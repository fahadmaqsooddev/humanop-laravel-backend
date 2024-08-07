<?php

namespace App\Http\Livewire\Client\Message;

use App\Helpers\Helpers;
use App\Models\Client\Follow\Follow;
use App\Models\Client\MessageThread\MessageThread;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Message extends Component
{
    public $chats = [], $message, $chat_user_id, $messages = [], $logged_in_user_id, $chat_user, $followers,

    $filter_text;

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

        $this->followers = Follow::followers();

        return view('livewire.client.message.message');
    }

    public function sendMessage(){

        $follow = Follow::followerExists($this->chat_user_id);

        if ($follow){

            $thread = MessageThread::createOrGetMessageThread($this->chat_user_id);

            $data['message_thread_id'] = $thread->id ?? null;

            $data['receiver_id'] = $this->chat_user_id;

            $data['message'] = $this->message;

            $data['sender_id'] = Helpers::getWebUser()->id;

            if (strlen(trim($this->message)) > 0){

                \App\Models\Client\Message\Message::createMessage($data);
            }

            $this->reset('message');

            $this->messages = \App\Models\Client\Message\Message::threadMessages($thread->id);

            $this->chats = MessageThread::chats();

        }else{

            toastr()->error("Follow first to send message", '');
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
}
