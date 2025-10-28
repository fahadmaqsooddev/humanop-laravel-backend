<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Feedback\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlueWebhookController extends Controller
{
    // app/Http/Controllers/Api/BlueWebhookController.php

    public function ticketUpdated(Request $request)
    {
        // 1) raw body (as received over HTTP)
        $raw = file_get_contents('php://input');

        // 2) header name used by Blue
        $sigHeader = $request->header('x-signature') ?? '';

        // 3) your shared secret (use as plain UTF-8 string, do NOT hex2bin)
        $secret = config('services.blue.webhook_secret');

        // 4) Build the exact JSON string Blue is expected to sign: JSON.stringify(payload)
        $toSign = $raw;
        if (str_starts_with(strtolower($request->header('content-type') ?? ''), 'application/json')) {
            try {
                $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
                // Minified JSON (like JSON.stringify): no spaces/newlines, UTF-8
                $toSign = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                // If parsing fails, fallback to raw (but JSON should parse)
            }
        }

        if (! $this->verifyBlueSignature($sigHeader, $toSign, $secret)) {
            Log::warning('Blue webhook invalid signature', [
                'header'      => $sigHeader,
                'len_raw'     => strlen($raw),
                'len_toSign'  => strlen($toSign),
            ]);
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // --- proceed: parse JSON & update ticket ---
        $blueTicketId = $request->input('ticket_id');
        $newStatus    = $request->input('status');
        $lastUpdate   = $request->input('last_update');

        if (! $blueTicketId) {
            return response()->json(['error' => 'missing ticket_id'], 400);
        }

//        $ticket = \App\Models\SupportTicket::where('blue_ticket_id', $blueTicketId)->first();
//        if ($ticket) {
//            $ticket->update([
//                'blue_status'         => $newStatus,
//                'blue_last_update'    => $lastUpdate,
//                'blue_last_synced_at' => now(),
//            ]);
//        }

        return response()->json(['ok' => true], 200);
    }

    protected function verifyBlueSignature(string $sigHeader, string $toSign, string $secret): bool
    {
        if ($sigHeader === '' || $secret === '') return false;

        // Blue sends lowercase hex (per your example)
        $provided = strtolower(trim($sigHeader, " \t\n\r\0\x0B\"'"));

        // HMAC-SHA256 over the minified JSON, using secret as UTF-8 string
        $raw = hash_hmac('sha256', $toSign, $secret, true);
        $hex = bin2hex($raw);

        return hash_equals($hex, $provided);
    }

}
