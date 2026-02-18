<?php

namespace App\Http\Controllers\Api\v4\ClientController;

use App\Helpers\v4\Helpers;
use App\Http\Controllers\Controller;
use App\Models\v4\Client\MessageThread\MessageThread;
use Illuminate\Http\Request;

class DirectController extends Controller
{
    public function directChat(Request $request)
    {

        try {

            $all_chats = MessageThread::directChat($request['user_id']);

            return Helpers::successResponse('All Chats', $all_chats);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
