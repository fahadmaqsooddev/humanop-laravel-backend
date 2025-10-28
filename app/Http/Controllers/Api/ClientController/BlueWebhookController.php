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
        $raw      = file_get_contents('php://input');                    // exact wire bytes
        $sig      = strtolower(trim($request->header('x-signature') ?? ''));
        $secret   = (string) config('services.blue.webhook_secret');     // plain string
        $method   = $request->getMethod();
        $path     = '/'.$request->path();
        $ctype    = strtolower($request->header('content-type') ?? '');

        // Prepare minified JSON (JSON.stringify-like)
        $min = $raw;
        if (str_starts_with($ctype, 'application/json')) {
            try {
                $min = json_encode(json_decode($raw, true, 512, JSON_THROW_ON_ERROR), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                // keep $min = $raw
            }
        }
        $normRaw = str_replace("\r\n", "\n", $raw);
        $normMin = str_replace("\r\n", "\n", $min);

        // All candidate strings Blue might be signing
        $candidates = [
            ['name' => 'raw',                   'str' => $raw],
            ['name' => 'minified',              'str' => $min],
            ['name' => 'raw_nl_normalized',     'str' => $normRaw],
            ['name' => 'minified_nl_normalized','str' => $normMin],
            ['name' => 'path+raw',              'str' => $path."\n".$raw],
            ['name' => 'path+minified',         'str' => $path."\n".$min],
            ['name' => 'method+path+raw',       'str' => $method."\n".$path."\n".$raw],
            ['name' => 'method+path+minified',  'str' => $method."\n".$path."\n".$min],
            ['name' => '[object Object]',       'str' => '[object Object]'], // bad sender update(obj)
        ];

        $matched = null;
        $matchedHex = null;

        foreach ($candidates as $cand) {
            $hex = bin2hex(hash_hmac('sha256', $cand['str'], $secret, true)); // full HMAC-SHA256 hex
            if (hash_equals($hex, $sig)) {
                $matched = $cand['name'];
                $matchedHex = $hex;
                break;
            }
        }

        if (!$matched) {
            Log::warning('Blue webhook invalid signature', [
                'received'    => $sig,
                'len_payload' => strlen($raw),
                'tried'       => array_column($candidates, 'name'),
                // DEBUG: print first 16 hex chars per candidate to compare quickly
                'debug_first16' => collect($candidates)->mapWithKeys(function($c) use ($secret) {
                    $hex = bin2hex(hash_hmac('sha256', $c['str'], $secret, true));
                    return [$c['name'] => substr($hex, 0, 16)];
                }),
            ]);
            return response()->json(['error'=>'invalid signature'], 403);
        }

        Log::info('Blue webhook signature matched', [
            'mode' => $matched,
            // 'hex' => $matchedHex, // optional
        ]);

        // SAFE: process payload
        $blueTicketId = $request->input('ticket_id');
        if (!$blueTicketId) {
            return response()->json(['error'=>'missing ticket_id'], 400);
        }

//        if ($ticket = SupportTicket::where('blue_ticket_id', $blueTicketId)->first()) {
//            $ticket->update([
//                'blue_status'         => $request->input('status'),
//                'blue_last_update'    => $request->input('last_update'),
//                'blue_last_synced_at' => now(),
//            ]);
//        }

        return response()->json(['ok'=>true], 200);
    }
}
