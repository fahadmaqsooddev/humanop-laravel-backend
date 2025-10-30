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

//    public function ticketUpdated(Request $request)
//    {
//        // ... inside your controller method
//
//        $raw = $request->getContent();
//        $sig = strtolower(trim($request->header('x-signature') ?? ''));
//        $secret = trim((string)config('services.blue.webhook_secret'));
//
//// 1. Calculate Expected Signature (standard)
//        $expected = bin2hex(hash_hmac('sha256', $raw, $secret, true));
//
//// 2. Calculate Expected Signature with trailing NEWLINE (\n)
//        $expected_with_lf = bin2hex(hash_hmac('sha256', $raw . "\n", $secret, true));
//
//// 3. Calculate Expected Signature with trailing CARRIAGE RETURN LINE FEED (\r\n)
//        $expected_with_crlf = bin2hex(hash_hmac('sha256', $raw . "\r\n", $secret, true));
//
//// --- NEW DEBUGGING ---
//        Log::debug('Expected Signature (Standard): ' . $expected);
//        Log::debug('Expected Signature (with \n): ' . $expected_with_lf);
//        Log::debug('Expected Signature (with \r\n): ' . $expected_with_crlf);
//        Log::debug('Received Signature: ' . $sig);
//        Log::debug('Raw Body: ' . $raw);
//// --- END NEW DEBUGGING ---
//
//// Check against all possibilities
//        if ($sig === '' || $secret === '' || (!hash_equals($expected, $sig) && !hash_equals($expected_with_lf, $sig) && !hash_equals($expected_with_crlf, $sig))) {
//            return response()->json(['error' => 'invalid signature'], 403);
//        }
//
//// ... success code
//    }

    public function ticketUpdated(Request $request)
    {
        Log::info("Blue Payload:\n" . json_encode($request['currentValue']['todoListId'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


//        $signature = $request->header('X-Blue-Signature');
//        $secret = config('services.blue.webhook_secret');
//
//        if (!$this->isValidSignature($request->getContent(), $signature, $secret)) {
//            Log::warning('Blue webhook invalid signature', ['sig' => $signature]);
//            return response()->json(['error' => 'invalid signature'], Response::HTTP_FORBIDDEN);
//        }

//        $blueTicketId = $request->input('ticket_id');
//        $newStatus = $request->input('status');
//        $lastUpdate = $request->input('last_update');
//
//        if (!$blueTicketId) {
//
//            Log::info("missing ticket_id:\n" . json_encode($blueTicketId, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
//
//
//            return response()->json(['error' => 'missing ticket_id'], Response::HTTP_BAD_REQUEST);
//        }
//
//        Log::info("Blue Ticket ID: " . $blueTicketId);
//
//        $ticket = Feedback::where('blue_ticket_id', $blueTicketId)->first();
//
//        if (!$ticket) {
//            // Could log and still 200 so Blue doesn't retry forever
//            Log::info('Webhook for unknown ticket', ['blue_ticket_id' => $blueTicketId]);
//            return response()->json(['ok' => true], Response::HTTP_OK);
//        }
//
//        $ticket->update([
//            'blue_status' => $newStatus,
//            'blue_last_update' => $lastUpdate,
//            'blue_last_synced_at' => now(),
//        ]);
//
//        return response()->json(['ok' => true], Response::HTTP_OK);
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
