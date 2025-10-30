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
        Log::info("Blue Payload:\n" . json_encode($request['currentValue'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


//        $signature = $request->header('X-Blue-Signature');
//        $secret = config('services.blue.webhook_secret');
//
//        if (!$this->isValidSignature($request->getContent(), $signature, $secret)) {
//            Log::warning('Blue webhook invalid signature', ['sig' => $signature]);
//            return response()->json(['error' => 'invalid signature'], Response::HTTP_FORBIDDEN);
//        }

        $blueTicketId = $request['currentValue']['id'];
        $newStatus = $request['currentValue']['todoListId'];
        $lastUpdate = $request['currentValue']['updatedAt'];

        if (!$blueTicketId) {

            return Helpers::validationResponse('missing ticket_id');

        }

        Log::info("Blue Ticket ID: " . $blueTicketId);

        $ticket = Feedback::where('blue_ticket_id', $blueTicketId)->first();

        if (!$ticket) {

            Log::info('Webhook for unknown ticket', ['blue_ticket_id' => $blueTicketId]);


            return response()->json(['ok' => true], Response::HTTP_OK);

        }

        $ticket->update([
            'blue_status' => $newStatus,
            'blue_last_update' => $lastUpdate,
            'blue_last_synced_at' => now(),
        ]);

        return Helpers::successResponse('Ticket Updated');

    }

    protected function isValidSignature(string $payload, ?string $headerSig, string $secret): bool
    {
        if (!$headerSig) {
            return false;
        }

        // HMAC SHA256 example
        $expected = hash_hmac('sha256', $payload, $secret);

        // timing safe compare
        return hash_equals($expected, $headerSig);
    }
}
