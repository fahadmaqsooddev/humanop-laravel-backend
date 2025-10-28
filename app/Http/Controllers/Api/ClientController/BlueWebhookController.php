<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Feedback\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlueWebhookController extends Controller
{
    public function ticketUpdated(Request $request)
    {
        $raw    = file_get_contents('php://input');                // exact body
        $sig    = strtolower(trim($request->header('x-signature') ?? ''));
        $secret = (string) config('services.blue.webhook_secret');

        if ($sig === '' || $secret === '') {
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // Compute candidates
        $sha256_full = bin2hex(hash_hmac('sha256', $raw, $secret, true)); // 64 hex
        $sha256_16   = substr($sha256_full, 0, 32);                        // first 16 bytes as hex
        $hmac_md5    = hash_hmac('md5', $raw, $secret);                    // 32 hex

        $matched = null;
        if (hash_equals($sha256_full, $sig)) {
            $matched = 'hmac-sha256-full';
        } elseif (hash_equals($sha256_16, $sig)) {
            $matched = 'hmac-sha256-truncated-16bytes';
        } elseif (hash_equals($hmac_md5, $sig)) {
            $matched = 'hmac-md5';
        }

        if (!$matched) {
            Log::warning('Blue webhook invalid signature', [
                'received'      => $sig,
                'sha256_full'   => $sha256_full,
                'sha256_16'     => $sha256_16,
                'hmac_md5'      => $hmac_md5,
                'len_payload'   => strlen($raw),
            ]);
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // Optional: log once to know which algorithm Blue is actually using
        Log::info('Blue webhook signature matched', ['mode' => $matched]);

//        // SAFE: process payload
//        $blueTicketId = $request->input('ticket_id');
//        if (!$blueTicketId) {
//            return response()->json(['error' => 'missing ticket_id'], 400);
//        }
//
//        if ($ticket = SupportTicket::where('blue_ticket_id', $blueTicketId)->first()) {
//            $ticket->update([
//                'blue_status'         => $request->input('status'),
//                'blue_last_update'    => $request->input('last_update'),
//                'blue_last_synced_at' => now(),
//            ]);
//        }
//
//        return response()->json(['ok' => true], 200);
    }

}
