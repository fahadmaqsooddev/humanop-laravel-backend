<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserInGroupRequest;
use App\Http\Requests\CheckThreadIdRequest;
use App\Http\Requests\CreateGroupThreadRequest;
use App\Http\Requests\RemoveUserInGroupRequest;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\Client\MessageThreadParticipant\MessageThreadParticipant;
use App\Models\Upload\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function React\Promise\all;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function allThreads(Request $request)
    {

        try {

            $all_chats = MessageThread::getMessageThread($request);

            return Helpers::successResponse('All Chats', $all_chats, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function showTHreads(CheckThreadIdRequest $request)
    {
        try {

            $messageThread = MessageThread::findOrFail($request->thread_id);

            $this->authorize('view', $messageThread);

            $show = MessageThread::LoadMessageThreads($messageThread);

            return Helpers::successResponse('Show threads', $show);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function createGroupChat(CreateGroupThreadRequest $request)
    {
        DB::beginTransaction();

        try {

            $upload_id = Upload::uploadFile($request['group_profile_image'], 200, 200, 'base64Image', 'png', true);

            $request['group_icon_id'] = $upload_id;

            $loginUser = $request->user();

            $group = MessageThread::createGroup($request, $loginUser->id);

            DB::commit();

            return Helpers::successResponse('New group created successfully.', $group);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function addUsersInGroup(AddUserInGroupRequest $request)
    {

        $messageThread = MessageThread::findOrFail($request->thread_id);

        $this->authorize('manage', $messageThread);

        DB::beginTransaction();

        try {

            $member = MessageThread::addUsers($request);

            DB::commit();

            return Helpers::successResponse('Members added successfully.', $member);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function removeUserInGroup(RemoveUserInGroupRequest $request)
    {

        $loginUser = Helpers::getUser();

        $user = MessageThreadParticipant::getSingleUser($loginUser['id'], $request->thread_id);

        if (!in_array($user->role, [0, 1])) {
            return Helpers::validationResponse('You cannot remove users because you have no permission to remove other users.');
        }

        $messageThread = MessageThread::findOrFail($request->thread_id);

        $this->authorize('manage', $messageThread);

        DB::beginTransaction();

        try {

            $member = MessageThread::removeUser($request, $messageThread);

            DB::commit();

            return Helpers::successResponse('Members remove successfully.', $member);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function removeMember(Request $request, MessageThread $messageThread, User $user)
    {
        $this->authorize('manage', $messageThread);
        $messageThread->participants()->detach($user->id);
        return response()->json(['ok' => true]);
    }

    public function setRole(Request $request, MessageThread $messageThread, User $user)
    {
        $this->authorize('manage', $messageThread);
        $role = (int)$request->input('role'); // 1=admin, 2=member
        abort_unless(in_array($role, [MessageThread::ROLE_ADMIN, MessageThread::ROLE_MEMBER], true), 422);
        $messageThread->participants()->updateExistingPivot($user->id, ['role' => $role]);
        return response()->json(['ok' => true]);
    }

}
