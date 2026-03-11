<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Http\Controllers\Controller;
use App\Models\v4\Client\BoostSession;
use App\Services\v4\EnergyBoostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BoostSessionController extends Controller
{

    public function start(Request $request, EnergyBoostService $energyBoostService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'protocol_type' => ['required', 'string'],
            'metadata' => ['nullable', 'array'],
        ]);

        $validator->validate();

        $session = $energyBoostService->startSession(
            $request->user()->id,
            $request->string('protocol_type')->toString(),
            $request->input('metadata', [])
        );

        return response()->json([
            'status' => 'ok',
            'session' => $session,
        ], 201);
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
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $session = $energyBoostService->finalizeSession(
            $session,
            (bool) $request->boolean('coherence_achieved')
        );

        return response()->json([
            'status' => 'ok',
            'session' => $session,
        ]);
    }

}
