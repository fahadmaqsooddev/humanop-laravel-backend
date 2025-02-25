<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Messages\DeleteChatRequest;
use App\Http\Requests\Api\Client\Messages\MessagesRequest;
use App\Http\Requests\Api\Client\Messages\SendMessageRequest;
use App\Models\Admin\Notification\Notification;
use App\Models\Client\Connection\Connection;
use App\Models\Client\Message\Message;
use App\Models\Client\MessageThread\MessageThread;
use Illuminate\Http\Request;
use App\Events\messages\MessageSent;
use App\Events\messages\NewMessage;

class MessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function chats(Request $request)
    {

        try {

            $name = $request->query('name');

            $all_chats = MessageThread::chats($name);

            return Helpers::successResponse('User chats', $all_chats);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function sendMessage(SendMessageRequest $request)
    {

        try {

            $message = new Message();

            $dataArray = $request->only($message->getFillable());

            $connection_exists = Connection::connectionExists($request->input('receiver_id'));

            if ($connection_exists) {

                $thread = MessageThread::createOrGetMessageThread($request->input('receiver_id'));

                $dataArray['message_thread_id'] = $thread->id ?? null;

                $dataArray['sender_id'] = Helpers::getUser()->id;

                $senderUserName = Helpers::getUser()['first_name'] . ' ' . Helpers::getUser()['last_name'];

                $message = Message::createMessage($dataArray);

                $heading = $senderUserName . " send you a message";

                event(new MessageSent($request->input('receiver_id'), $request->input('message'), $message->created_at, $heading));

                Helpers::OneSignalApiUsed($request->input('receiver_id'), $heading, $request->input('message'));

                Notification::createNotification('message sent', $heading, null, $request->input('receiver_id'), 1, Admin::MESSAGE_SEND_NOTIFICATION);

                event(new NewMessage(Helpers::getUser()->id,$request->input('receiver_id'),$request->input('message'),$message['created_at']));
                // Helpers::OneSignalApiUsed($request->input('receiver_id'), 'New Message Received', $request->input('message'));
                return Helpers::successResponse('Message sent', ['thread_id' => $thread->id]);

            } else {

                return Helpers::validationResponse('Connect first to send message');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function messages(MessagesRequest $request)
    {

        try {

            $messages = Message::threadMessages($request->input('message_thread_id'));

            return Helpers::successResponse('Thread messages', $messages);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function deleteChat(DeleteChatRequest $request)
    {

        try {

            MessageThread::deleteMessageThreadFromApi($request->input('message_thread_id'));

            return Helpers::successResponse('Chat deleted');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
