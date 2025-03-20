<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Notification\Notification;

class B2BNotificationController extends Controller
{
    //
    protected $auth;

    protected $user;

    public function __construct(User $user)
    {
        $this->auth = Auth::guard('api');

        $this->user = $user;
    }

    public function B2Bnotifications()
    {
        try {
     

            $notifications = Notification::allB2BNotification();

            return Helpers::successResponse('All B2B Notification', $notifications);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

}
