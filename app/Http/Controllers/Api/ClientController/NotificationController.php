<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
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

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
