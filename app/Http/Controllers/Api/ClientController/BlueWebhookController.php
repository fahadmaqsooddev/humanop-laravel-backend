<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Feedback\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BlueWebhookController extends Controller
{

    /**
     * Blue -> HumanOp
     * Expected JSON body example (adjust to match Blue):
     * {
     *   "ticket_id": "BLU-12345",
     *   "status": "in_progress",
     *   "last_update": "Agent John replied: We are checking your billing issue.",
     *   "updated_at": "2025-10-28T15:02:00Z"
     * }
     *
     * Blue should also send X-Blue-Signature header = HMAC-SHA256(payload, BLUE_WEBHOOK_SECRET)
     */

    public function ticketUpdated(Request $request)
    {

        Log::info('check webhook');

        // --- 1. Verify signature ---

//        $signatureHeader = $request->header('X-Blue-Signature');
//
//        $secret = config('services.blue.webhook_secret');
//
//        if (!$this->isValidSignature($request->getContent(), $signatureHeader, $secret)) {
//
//            Log::warning('Blue webhook invalid signature', ['sig' => $signatureHeader,]);
//
//            return response()->json(['error' => 'invalid signature'], Response::HTTP_FORBIDDEN);
//
//        }
//
//
//        // --- 2. Extract payload ---
//        $blueTicketId = $request->input('ticket_id');
//
//        $newStatus = $request->input('status');
//
//        $lastUpdate = $request->input('last_update');
//
//        if (!$blueTicketId) {
//
//            return response()->json(['error' => 'missing ticket_id'], Response::HTTP_BAD_REQUEST);
//
//        }
//
//        // --- 3. Find local ticket ---
//
//        /** @var Feedback|null $ticket */
//
//        $ticket = Feedback::where('blue_ticket_id', $blueTicketId)->first();
//
//        if (!$ticket) {
//            // We don't want Blue to retry forever if user deleted ticket locally.
//            Log::info('Webhook for unknown ticket', ['blue_ticket_id' => $blueTicketId,]);
//
//            return response()->json(['ok' => true], Response::HTTP_OK);
//
//        }
//
//        // --- 4. Update local cache ---
//        $ticket->update([
//            'blue_status' => $newStatus,
//            'blue_last_update' => $lastUpdate,
//            'blue_last_synced_at' => now(),
//        ]);
//
//        return Helpers::successResponse('blue ticket status updated');



        return response()->json(['ok' => true], Response::HTTP_OK);

    }

    /**
     * HMAC SHA256 validation: timing-safe compare.
     */

    protected function isValidSignature(string $payload, ?string $headerSig, string $secret): bool
    {
        if (!$headerSig) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $headerSig);
    }

}
