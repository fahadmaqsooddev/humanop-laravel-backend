<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptOrRejectGroupRequest;
use App\Http\Requests\AddUserInGroupRequest;
use App\Http\Requests\ChangeParticipantRoleRequest;
use App\Http\Requests\CheckThreadIdRequest;
use App\Http\Requests\CreateGroupThreadRequest;
use App\Http\Requests\EditGroupRequest;
use App\Http\Requests\RemoveUserInGroupRequest;
use App\Http\Requests\SendGroupRequest;
use App\Models\Admin\Notification\Notification;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\Client\MessageThreadParticipant\MessageThreadParticipant;
use App\Models\Client\MessageThreadRequest;
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

//            if (Helpers::getUser()['group_filter'] === 0) {

                $all_chats = MessageThread::getMyMessageThread($request);

//            } else {
//
//                $all_chats = MessageThread::getAllMessageThread($request);
//
//            }

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

            $user = Helpers::getUser();

            if (($user['plan_name'] == "Freemium") && ($user['beta_breaker_club'] != Admin::BETA_BREAKER_CLUB)) {

                return Helpers::validationResponse('You cannot create a group because your current Freemium plan does not include this feature.');

            }

            if (($user['plan_name'] == "Freemium") && ($user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB)) {

                $groups = MessageThread::allGroups($user);

                if (count($groups) >= 3) {

                    return Helpers::validationResponse('You have already created 3 groups. Please upgrade your plan to create more groups.');
                }
            }

            if (!empty($request['group_profile_image'])) {

                $upload_id = Upload::uploadFile($request['group_profile_image'], 200, 200, 'base64Image', 'png', true);

                $request['group_icon_id'] = $upload_id;

            } else {

                $request['group_icon_id'] = null;

            }

            $loginUser = $request->user();

            $group = MessageThread::createGroup($request, $loginUser->id);

            DB::commit();

            return Helpers::successResponse('New group created successfully.', $group);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function editGroup(EditGroupRequest $request)
    {
        DB::beginTransaction();

        try {

            if (!empty($request['group_profile_image'])) {

                $upload_id = Upload::uploadFile($request['group_profile_image'], 200, 200, 'base64Image', 'png', true);

                $request['group_icon_id'] = $upload_id;

            }

            $loginUser = $request->user();

            $group = MessageThread::editGroup($request, $loginUser->id);

            DB::commit();

            return Helpers::successResponse('group edited successfully.', $group);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function addUsersInGroup(AddUserInGroupRequest $request)
    {

        $messageThread = MessageThread::findOrFail($request->thread_id);

//        $this->authorize('manage', $messageThread);

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

        if (!empty($request['member_id'])) {

            if (!in_array($user->role, [0, 1])) {
                return Helpers::validationResponse('You cannot remove Member because you have no permission to remove other users.');
            }

            $messageThread = MessageThread::findOrFail($request->thread_id);

            $this->authorize('manage', $messageThread);

        }

        DB::beginTransaction();

        try {

            if (!empty($request['user_id']) && $request['user_id'] == $loginUser['id']) {

                MessageThreadParticipant::removeUser($request);

                DB::commit();

                return Helpers::successResponse('You have been removed from this group.');

            } elseif (!empty($request['member_id'])) {

                MessageThread::removeUser($request, $messageThread);

                DB::commit();

                return Helpers::successResponse('Member remove successfully.');


            } else {

                return Helpers::validationResponse('User not found');

            }

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

    public function setRole(ChangeParticipantRoleRequest $request)
    {

        $messageThread = MessageThread::findOrFail($request->thread_id);

        $this->authorize('manage', $messageThread);

        DB::beginTransaction();

        try {

            $member = MessageThreadParticipant::changeRole($request);

            DB::commit();

            return Helpers::successResponse('Role changed successfully.', $member);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function changedGroupFilter(Request $request)
    {

        try {

            User::changeGroupChatFIlter($request['group_chat_filter']);

            return Helpers::successResponse('Group Chat Filter updated successfully.');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function sendGroupRequest(SendGroupRequest $request)
    {
        try {

            $data = $request->only(['thread_id', 'owner_id', 'member_id']);

            $checkGroup = MessageThread::checkMemberExistInGroup($data);

            if (!empty($checkGroup) && $checkGroup->participants->isNotEmpty()) {

                return Helpers::validationResponse('This member already exists in the group.');

            }

            $checkRequest = MessageThreadRequest::createGroupRequest($data);

            if ($checkRequest === true) {

                return Helpers::successResponse('Group request sent successfully.');

            }

            return Helpers::validationResponse('Request has already been sent.');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function acceptGroupRequest(AcceptOrRejectGroupRequest $request)
    {
        try {

            DB::beginTransaction();

            $data = $request->only(['thread_id', 'member_id', 'accept_or_reject']);

            $checkOrCreateParticipant = MessageThreadParticipant::createUser($data);

            if ($checkOrCreateParticipant == true) {

                MessageThreadRequest::deleteRequest($data);

                $group = MessageThread::where('id', $data['thread_id'])->first();

                if ($data['accept_or_reject'] == 1) {

                    $msg = "Congratulations! You have been added to the group '{$group->name}'.";

                    Notification::createNotification('Accept Group Request', $msg, '', $data['member_id'], 0, Admin::ACCEPT_REQUEST_NOTIFICATION, Admin::B2C_NOTIFICATION);

                    broadcast(new \App\Events\AcceptOrRejectGroupRequest($data['member_id'], 'Group request accepted ', $msg))->toOthers();

                    DB::commit();

                    return Helpers::successResponse('Group request accepted successfully.');

                } else {

                    $msg = "Your request to join the group '{$group->name}' has been declined by the group owner.";

                    Notification::createNotification('Reject Group Request', $msg, '', $data['member_id'], 0, Admin::REJECT_REQUEST_NOTIFICATION, Admin::B2C_NOTIFICATION);

                    broadcast(new \App\Events\AcceptOrRejectGroupRequest($data['member_id'], 'Group request rejected ', $msg))->toOthers();

                    DB::commit();

                    return Helpers::validationResponse('Group request rejected successfully.');
                }

            } else {

                DB::rollBack();

                return Helpers::validationResponse('This member already exists in the group.');

            }

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

}
