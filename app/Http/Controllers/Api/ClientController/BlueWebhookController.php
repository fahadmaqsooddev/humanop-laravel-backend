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

    public function ticketUpdated(Request $request)
    {


        Log::info(print_r($request->header('x-signature'), true));

        Log::info(print_r($request->all(), true));



        // --- 1. Verify signature ---

        $signatureHeader = $request->header('x-signature');

        $secret = config('services.blue.webhook_secret');

        if (!$this->isValidSignature($request->getContent(), $signatureHeader, $secret)) {

            Log::warning('Blue webhook invalid signature', ['sig' => $signatureHeader,]);

            return response()->json(['error' => 'invalid signature'], Response::HTTP_FORBIDDEN);

        }


        // --- 2. Extract payload ---
        $blueTicketId = $request->input('ticket_id');

        $newStatus = $request->input('status');

        $lastUpdate = $request->input('last_update');

        if (!$blueTicketId) {

            return response()->json(['error' => 'missing ticket_id'], Response::HTTP_BAD_REQUEST);

        }

        // --- 3. Find local ticket ---

        $ticket = Feedback::where('blue_ticket_id', $blueTicketId)->first();

        if (!$ticket) {
            // We don't want Blue to retry forever if user deleted ticket locally.
            Log::info('Webhook for unknown ticket', ['blue_ticket_id' => $blueTicketId,]);

            return response()->json(['ok' => true], Response::HTTP_OK);
        }

        // --- 4. Update local cache ---
        $ticket->update([
            'blue_status' => $newStatus,
            'blue_last_update' => $lastUpdate,
            'blue_last_synced_at' => now(),
        ]);

        return Helpers::successResponse('blue ticket status updated');

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
