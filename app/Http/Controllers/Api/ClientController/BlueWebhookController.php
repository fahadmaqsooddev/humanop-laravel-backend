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
    public function ticketUpdated(\Illuminate\Http\Request $request)
    {
        $raw      = file_get_contents('php://input'); // exact bytes
        $sig      = $request->header('x-signature') ?? '';
        $secret   = config('services.blue.webhook_secret');

        // Build candidates
        $candidates = [];

        // 1) Minified JSON (JSON.stringify-like)
        $toSignJson = $raw;
        if (str_starts_with(strtolower($request->header('content-type') ?? ''), 'application/json')) {
            try {
                $decoded   = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
                $toSignJson = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                // keep $toSignJson = $raw if parsing fails
            }
        }
        $candidates[] = $toSignJson;

        // 2) Raw body as-is
        $candidates[] = $raw;

        // 3) Node object coerced to string
        $candidates[] = '[object Object]';

        if (! $this->verifyAgainstAny($sig, $secret, $candidates)) {
            Log::warning('Blue webhook invalid signature', [
                'header'      => $sig,
                'len_raw'     => strlen($raw),
                'len_json'    => strlen($toSignJson),
            ]);
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // proceed to handle payload
        $blueTicketId = $request->input('ticket_id');
        if (! $blueTicketId) {
            return response()->json(['error' => 'missing ticket_id'], 400);
        }

//        $ticket = \App\Models\SupportTicket::where('blue_ticket_id', $blueTicketId)->first();
//        if ($ticket) {
//            $ticket->update([
//                'blue_status'         => $request->input('status'),
//                'blue_last_update'    => $request->input('last_update'),
//                'blue_last_synced_at' => now(),
//            ]);
//        }

        return response()->json(['ok' => true], 200);
    }

    protected function verifyAgainstAny(string $sigHeader, string $secret, array $candidates): bool
    {
        if ($sigHeader === '' || $secret === '') return false;

        $provided = strtolower(trim($sigHeader, " \t\n\r\0\x0B\"'"));

        foreach ($candidates as $toSign) {
            if (!is_string($toSign)) continue;

            // secret is a plain UTF-8 string (per Blue doc example)
            $rawMac = hash_hmac('sha256', $toSign, $secret, true);
            $hexMac = bin2hex($rawMac);
            $b64Mac = base64_encode($rawMac);

            if (hash_equals($hexMac, $provided) || hash_equals($b64Mac, $provided)) {
                return true;
            }
        }

        return false;
    }


}
