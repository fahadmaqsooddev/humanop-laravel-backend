<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\v4\Api\Client\Notification\NotificationRequest;
use App\Models\Admin\Notification\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\Admin\Admin;

class NotificationController extends Controller
{

    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth:api');

        $this->user = Helpers::getUser();

    }

    public function notifications(Request $request)
    {
        try {

            $status = $request->input('status', null);
            $pagination = $request->input('pagination');
            $perPage = (int) $request->input('per_page', 10);
            $userId=$this->user->id;
            $notifications = Notification::allB2CNotification($status, $pagination, $perPage,$userId);
            return Helpers::successResponse('All Notification', $notifications, $pagination);

        } catch (\Exception $exception) {
            // Handle unexpected errors
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function allNotificationsCount()
    {
        try {


            $notifications = Notification::allB2CMessageNotificationCount();

            return Helpers::successResponse('All Notification Count', $notifications);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function readNotification(NotificationRequest $request)
    {
        try {

            $getNotification = Notification::getNotification($request['notification_id']);

            if (!empty($getNotification)) {

                if ($getNotification['read'] != 1) {

                    Notification::readNotification($getNotification['id']);

                    return Helpers::successResponse('Read Notification Successfully');

                } else {


                   return Helpers::validationResponse('already read Notification');
                }
            }
            else{

                return Helpers::validationResponse('Notification not found');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    // public function allReadNotification(NotificationRequest $request)
    // {
    //     try {

    //         $unreadFound = false;

    //         $allNotifications = Notification::allB2CNotification(null,false,null,$this->user->id);

    //         if (empty($allNotifications)) {

    //             return Helpers::validationResponse('Notification not found');

    //         }

    //         $notificationStatus = $request->input('notification_status');


    //         foreach ($allNotifications as $notification) {

    //             if($notificationStatus == Admin::NOTIFICATION_STATUS_READ){  // Change Unread 0 to Read 1 
    //                 Notification::readNotification($notification['id']);
    //                 $unreadFound = true;
    //             } else { // Change Read 1 to Unread 0
    //                 Notification::noReadNotification($notification['id']);
    //                 $unreadFound = false;
    //             }
    //         }

    //         if ($unreadFound) {
    //             return Helpers::successResponse('Unread notifications marked as read successfully');
    //         } else {
    //             return Helpers::successResponse('read notifications marked as unread successfully');
    //         }

    //     } catch (\Exception $exception) {

    //         return Helpers::serverErrorResponse($exception->getMessage());

    //     }

    // }



    public function allReadNotification(NotificationRequest $request)
    {
        try {

            $status = $request->input('notification_status');

            $userId = $this->user->id;

            $updated = Notification::updateB2CNotificationStatus($userId, $status);

            if (!$updated) {
                return Helpers::validationResponse('Notification not found');
            }

            if ($status == Admin::NOTIFICATION_STATUS_READ) {
                return Helpers::successResponse('Notifications marked as read successfully');
            }

            return Helpers::successResponse('Notifications marked as unread successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function deleteNotification(NotificationRequest $request)
    {
        try {

            $notifications = Notification::deleteNotification($request['notification_id']);

            if ($notifications == true)
            {
                return Helpers::successResponse('Notification delete successfully');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
