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

// Controller method
    public function ticketUpdated(Request $request)
    {
        $payload   = $request->getContent(); // RAW body, do not json_encode()
        $signature = $request->header('x-signature') ?? '';
        $secret    = config('services.blue.webhook_secret');

        if (! $this->isValidSignature($payload, $signature, $secret)) {
            Log::warning('Blue webhook invalid signature', [
                'header'       => $signature,
                'computed_hex' => hash_hmac('sha256', $payload, $secret), // for comparison
                'has_secret'   => $secret !== '',
                'len_payload'  => strlen($payload),
            ]);
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // ... proceed with reading inputs and updating the ticket
    }

// Verifier
    protected function isValidSignature(string $payload, ?string $headerSig, string $secret): bool
    {
        if (! $headerSig || $secret === '') {
            return false;
        }

        // Normalize header (strip prefix, quotes, spaces, lowercase)
        $provided = trim($headerSig, " \t\n\r\0\x0B\"'");
        if (str_starts_with(strtolower($provided), 'sha256=')) {
            $provided = substr($provided, 7);
        }
        $provided = strtolower($provided);

        // Our expected hex (hash_hmac returns lowercase hex by default)
        $expected = hash_hmac('sha256', $payload, $secret); // hex lowercase

        // Timing-safe compare
        return hash_equals($expected, $provided);
    }


}
