<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Feedback\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ParagonIE\Sodium\Core\Curve25519\Fe;
use Symfony\Component\HttpFoundation\Response;

class BlueWebhookController extends Controller
{
    public function ticketUpdated(Request $request)
    {
        Log::info(print_r($request->header(), true));
        // Always use the exact raw body bytes
        $payload   = file_get_contents('php://input');
        $sigHeader = $request->header('x-signature') ?? '';
        $tsHeader  = $request->header('X-Blue-Timestamp') ?? ''; // if Blue sends it
        $secretEnv = config('services.blue.webhook_secret');      // your hex string

        if (! $this->isValidSignature($payload, $sigHeader, $secretEnv, $tsHeader)) {
            Log::warning('Blue webhook invalid signature', [
                'header'      => $sigHeader,
                'timestamp'   => $tsHeader,
                'len_payload' => strlen($payload),
            ]);
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // --- proceed: parse + update ---
        $blueTicketId = $request->input('ticket_id');
        $newStatus    = $request->input('status');
        $lastUpdate   = $request->input('last_update');

        if (! $blueTicketId) {
            return response()->json(['error' => 'missing ticket_id'], 400);
        }

        $ticket = Feedback::where('blue_ticket_id', $blueTicketId)->first();
        if ($ticket) {
            $ticket->update([
                'blue_status'         => $newStatus,
                'blue_last_update'    => $lastUpdate,
                'blue_last_synced_at' => now(),
            ]);
        }

        return response()->json(['ok' => true], 200);
    }

    protected function isValidSignature(
        string $payload,
        string $sigHeader,
        string $secretEnv,
        ?string $timestamp = null
    ): bool {
        if ($sigHeader === '' || $secretEnv === '') return false;

        // Normalize header (accept "sha256=<hex>" or plain hex)
        $provided = trim($sigHeader, " \t\n\r\0\x0B\"'");
        if (str_starts_with(strtolower($provided), 'sha256=')) {
            $provided = substr($provided, 7);
        }
        $provided = strtolower($provided); // hex

        // Secret is hex-encoded; decode to raw key bytes
        $key = (ctype_xdigit($secretEnv) && (strlen($secretEnv) % 2 === 0))
            ? hex2bin($secretEnv)
            : $secretEnv;

        // Candidates Blue might sign
        $candidates = [$payload];
        if ($timestamp) {
            // common pattern: "<timestamp>.<payload>"
            $candidates[] = "{$timestamp}.{$payload}";
            // optional: reject stale timestamps (±5 min)
            if (!ctype_digit((string)$timestamp) || abs(time() - (int)$timestamp) > 300) {
                // comment this return if Blue doesn't guarantee a timestamp window
                // return false;
            }
        }

        foreach ($candidates as $toSign) {
            $expectedHex = bin2hex(hash_hmac('sha256', $toSign, $key, true));
            if (hash_equals($expectedHex, $provided)) {
                return true;
            }
        }

        return false;
    }
}
