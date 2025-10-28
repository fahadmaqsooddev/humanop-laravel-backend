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

    // Controller
    public function ticketUpdated(Request $request)
    {
        $payload   = file_get_contents('php://input'); // raw body
        $sigHeader = $request->header('x-signature') ?? '';
        $secret    = config('services.blue.webhook_secret');    // "6760d8bb..."

        if (! $this->isValidSignature($payload, $sigHeader, $secret)) {
            Log::warning('Blue webhook invalid signature', [
                'header'       => $sigHeader,
                'len_payload'  => strlen($payload),
            ]);
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // ... proceed to parse and update ticket
    }

// Verifier supports: hex signature (plain or "sha256=<hex>") and hex-encoded secret
    protected function isValidSignature(string $payload, ?string $headerSig, string $secret): bool
    {
        if (! $headerSig || $secret === '') return false;

        // normalize header
        $provided = trim($headerSig, " \t\n\r\0\x0B\"'");
        if (str_starts_with(strtolower($provided), 'sha256=')) {
            $provided = substr($provided, 7);
        }
        $provided = strtolower($provided); // hex compare

        // if secret looks like hex, decode to raw bytes
        $key = (ctype_xdigit($secret) && (strlen($secret) % 2 === 0))
            ? hex2bin($secret)
            : $secret;

        // compute expected hex
        $expected = bin2hex(hash_hmac('sha256', $payload, $key, true));

        return hash_equals($expected, $provided);
    }



}
