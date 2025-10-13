<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\User;
use App\Services\Chat\DirectThread;
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
