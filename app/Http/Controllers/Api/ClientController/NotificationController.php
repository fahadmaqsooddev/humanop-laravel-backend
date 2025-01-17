<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Notification\NotificationRequest;
use App\Models\Admin\Notification\Notification;
use App\Models\User;
use Illuminate\Http\Request;

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

            $notifications = Notification::allNotification();

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

                Helpers::validationResponse('Notification not found');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
