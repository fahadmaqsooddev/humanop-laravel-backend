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
            ->where('user_id',$request->user()->id)

            ->whereNull('acknowledged_at')

            ->where(function($q){
                $q->whereNull('expires_at')
                    ->orWhere('expires_at','>',now());
            })

            ->orderByDesc('detected_at')

            ->limit(5)

            ->get();

        return Helpers::successResponse('Events',$events);

    }

    /*
    |----------------------------------------
    | Acknowledge event
    |----------------------------------------
    */
    public function acknowledge(Request $request,$eventId)
    {

        $event = Event::query()
            ->where('id',$eventId)
            ->where('user_id',$request->user()->id)
            ->firstOrFail();

        $event->update([
            'acknowledged_at'=>now()
        ]);

        return Helpers::successResponse('Event acknowledged');

    }


}
