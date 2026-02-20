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
use App\Models\Client\MessageRead\MassageRead;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\Upload\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\messages\MessageSent;
use App\Events\messages\NewMessage;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {

            $message = new Message();

            $dataArray = $request->only($message->getFillable());

            $connection_exists = Connection::connectionExists($request->input('receiver_id'));

            if ($connection_exists) {

                $thread = MessageThread::createOrGetMessageThread($request->input('receiver_id'));

                $dataArray['message_thread_id'] = $thread->id ?? null;

                $dataArray['sender_id'] = Helpers::getUser()->id;

                if ($dataArray['upload_file']) {

                    $dataArray['upload_id'] = Upload::uploadFile($dataArray['upload_file'], 200, 200, 'base64Image', 'png', true);

                }

                $senderUserName = Helpers::getUser()['first_name'] . ' ' . Helpers::getUser()['last_name'];

                $message = Message::createMessage($dataArray);

                $heading = $senderUserName . " send you a message";

//                event(new MessageSent($request->input('receiver_id'), $request->input('message'), $message->created_at, $heading));

//                Helpers::OneSignalApiUsed($request->input('receiver_id'), $heading, $request->input('message'));

                Notification::createNotification('message sent', $heading, null, $request->input('receiver_id'), 1, Admin::MESSAGE_SEND_NOTIFICATION, Admin::B2C_NOTIFICATION, Helpers::getUser()['id']);

                event(new NewMessage(Helpers::getUser()->id, $request->input('receiver_id'), $request->input('message'), $message['created_at']));
                // Helpers::OneSignalApiUsed($request->input('receiver_id'), 'New Message Received', $request->input('message'));

                DB::commit();

                return Helpers::successResponse('Message sent', ['thread_id' => $thread->id]);
            } else {

                return Helpers::validationResponse('Connect first to send message');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
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


    public function allMessages(Request $request)
    {


        $messageThread = MessageThread::findOrFail($request->thread_id);

        $this->authorize('view', $messageThread);

        try {
            $messages = $messageThread->messages()
                ->with(['sender:id,first_name,last_name,image_id'])
                ->paginate(50);



            // Modify messages: set default sender if missing
            $messages->getCollection()->transform(function ($message) {
                $sender = $message->sender;
                if (empty($message->sender_id) || !$message->sender) {
                    $message->sender_id = '001';
                    $message->sender = (object)[
                        'id' => '001',
                        'first_name' => 'HAi',
                        'last_name' => 'Chat',
                        'photo_url' => (object)[
                            'url' => config('client_url.admin_dashboard_url') . '/media/files/b02ojvwqbkazzdfyvpzt/zNzaCv2gBT.png',
                        ],
                    ];
                }

                // Ensure the sender is included when converting to JSON
                $message->setRelation('sender', collect([
                    'first_name' => $sender->first_name,
                    'last_name'  => $sender->last_name,
                    'photo_url'  => $sender->photo_url,
                ]));


                return $message;
            });

            return Helpers::successResponse('All Messages', $messages);
        } catch (\Exception $exception) {
            DB::rollBack();
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function storeMessages(SendMessageRequest $request)
    {

        $messageThread = MessageThread::findOrFail($request->thread_id);

        $this->authorize('view', $messageThread);

        DB::beginTransaction();

        try {

            if ($request['upload_file']) {

                $request['upload_id'] = Upload::uploadFile($request['upload_file'], 200, 200, 'base64Image', 'png', true);

            }

            $message = Message::createMessage($request, $messageThread);

            broadcast(new MessageSent($message))->toOthers();

            $senderUserName = Helpers::getUser()['first_name'] . ' ' . Helpers::getUser()['last_name'];

            $heading = $senderUserName . " send you a message";

            Notification::createNotification('message sent', $heading, null, Helpers::getUser()->id, 1, Admin::MESSAGE_SEND_NOTIFICATION, Admin::B2C_NOTIFICATION, Helpers::getUser()['id']);

            DB::commit();

            return Helpers::successResponse('message', $message);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function markRead(Request $request, MessageThread $messageThread)
    {
        $this->authorize('view', $messageThread);

        $lastMessageId = (int)$request->input('last_message_id');
        if ($lastMessageId <= 0) return response()->json(['ok' => true]);

        // Per-user receipt for that last message (bookmark)
        MassageRead::updateOrCreate(
            ['message_id' => $lastMessageId, 'user_id' => Helpers::getUser()->id],
            ['read_at' => Carbon::now()]
        );

//        broadcast(new MessageReadUpdated($messageThread->id, $request->user()->id, $lastMessageId))->toOthers();

        return response()->json(['ok' => true]);
    }

}
