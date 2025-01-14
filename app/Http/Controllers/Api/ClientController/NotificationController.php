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

    public function readnotification(Request $request){
        try {
           
        $user = Helpers::getUser();

        
        if (empty($request['notification_id'])) {
            return Helpers::validationResponse('Notification ID Not Found');
        }
        
        
        $notification = Notification::where('id', $request['notification_id'])
            ->where('user_id', $user->id)
            ->first();
        
        
        if (!$notification) {
            return Helpers::validationResponse('Notification Not Found');
        }
        
        
        if ($notification->read) {
            return Helpers::validationResponse('Notification Already Read');
        }
        
        
        $notification->update(['read' => 1]);
        return Helpers::successResponse('Notification marked as read');
        

        
        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
       
    }
}
