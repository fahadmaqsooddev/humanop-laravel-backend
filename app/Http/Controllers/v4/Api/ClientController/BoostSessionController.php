<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\v4\Client\BoostSession;
use App\Models\v4\Client\Event;
use App\Services\v4\EnergyBoostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BoostSessionController extends Controller
{

    public function start(Request $request, EnergyBoostService $energyBoostService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'protocol_type' => ['required', 'string'],
            'metadata' => ['nullable', 'array'],
        ]);

        $validator->validate();

        $user = Helpers::getUser();

        $eventId = $request->integer('event_id');

        // Validate event belongs to the current user
        $event = Event::query()
            ->where('id', $eventId)
            ->where('user_id', $user->id)
            ->first();

        if (!$event) {

            return Helpers::unProcessableEntity('Event not found');
        }

        // Validate event is still active (not expired)
        if ($event->expires_at && $event->expires_at->isPast()) {

            return Helpers::unProcessableEntity('Event has expired');
        }

        // Validate no boost session already exists for this event
        $existingSession = BoostSession::query()
            ->where('event_id', $eventId)
            ->exists();

        if ($existingSession) {

            return Helpers::unProcessableEntity('A boost session already exists for this event');
        }

        $session = $energyBoostService->startSession(
            $user->id,
            $eventId,
            $request->string('protocol_type')->toString(),
            $request->input('metadata', [])
        );

        return Helpers::successResponse('Session started successfully', $session);

    }

    public function end(Request $request, EnergyBoostService $energyBoostService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_id' => ['required', 'integer', 'exists:boost_sessions,id'],
            'coherence_achieved' => ['nullable', 'boolean'],
        ]);

        $validator->validate();

        $session = BoostSession::query()
            ->where('id', $request->integer('session_id'))
            ->where('user_id', Helpers::getUser()->id)
            ->firstOrFail();

        $session = $energyBoostService->finalizeSession(
            $session,
            (bool) $request->boolean('coherence_achieved')
        );

        return Helpers::successResponse('Session ended successfully', $session);

    }

}
