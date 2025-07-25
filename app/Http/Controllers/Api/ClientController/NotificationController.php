<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Notification\NotificationRequest;
use App\Models\Admin\Notification\Notification;
use App\Models\User;
use Illuminate\Http\Client\Request;

class NotificationController extends Controller
{

    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth:api');

        $this->user = $user;

    }

    public function notifications()
    {
        try {

            $notifications = Notification::allB2CNotification();

            return Helpers::successResponse('All Notification', $notifications);

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

    public function allReadNotification(NotificationRequest $request)
    {
        try {

            if ($request['notification_id']){

                $updatedToRead = 0;

                $updatedToUnread = 0;

                foreach ($request['notification_id'] as $notificationId) {

                    $checkNotification = Notification::getNotification($notificationId);

                    if ($checkNotification['read'] == 1)
                    {
                        Notification::noReadNotification($checkNotification['id']);

                        $updatedToUnread++;

                    }else{

                        Notification::readNotification($checkNotification['id']);

                        $updatedToRead++;
                    }

                }

                if ($updatedToRead) {

                    $message = 'Notifications marked as read successfully.';

                } elseif ($updatedToUnread) {

                    $message = 'Notifications marked as unread successfully.';

                } else {

                    $message = 'No changes were made. Notifications were already in desired state.';

                }

                return Helpers::successResponse($message);

            }else{

                $unreadFound = false;

                $allNotifications = Notification::allB2CNotification();

                if (empty($allNotifications)) {

                    return Helpers::validationResponse('Notification not found');

                }

                foreach ($allNotifications as $notification) {

                    if ($notification['read'] != 1) {

                        Notification::readNotification($notification['id']);

                        $unreadFound = true;

                    }else
                    {
                        Notification::noReadNotification($notification['id']);

                        $unreadFound = false;
                    }

                }

            }

            if ($unreadFound) {

                return Helpers::successResponse('Unread notifications marked as read successfully');

            } else {

                return Helpers::successResponse('read notifications marked as unread successfully');

            }

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
