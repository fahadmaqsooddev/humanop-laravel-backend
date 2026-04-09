<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\v4\Client\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    /*
       |----------------------------------------
       | Fetch active events
       |----------------------------------------
       */
    public function index(Request $request)
    {

        $events = Event::query()
            ->where('user_id',Helpers::getUser()->id)

            ->whereNull('acknowledged_at')

            ->where(function($q){
                $q->whereNull('expires_at')
                    ->orWhere('expires_at','>',now());
            })

            ->orderByDesc('detected_at')

            ->limit(5)

            ->get()

            ->each(function ($event) {
                $event->protocol_duration_seconds = (int) config(
                    "humanop.protocol_durations.{$event->recommended_protocol}", 300
                );
            });

        return Helpers::successResponse('Events',$events);

    }

}
