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
        // ... inside your controller method

        $raw = $request->getContent();
        $sig = strtolower(trim($request->header('x-signature') ?? ''));
        $secret = trim((string)config('services.blue.webhook_secret'));

// 1. Calculate Expected Signature (standard)
        $expected = bin2hex(hash_hmac('sha256', $raw, $secret, true));

// 2. Calculate Expected Signature with trailing NEWLINE (\n)
        $expected_with_lf = bin2hex(hash_hmac('sha256', $raw . "\n", $secret, true));

// 3. Calculate Expected Signature with trailing CARRIAGE RETURN LINE FEED (\r\n)
        $expected_with_crlf = bin2hex(hash_hmac('sha256', $raw . "\r\n", $secret, true));

// --- NEW DEBUGGING ---
        Log::debug('Expected Signature (Standard): ' . $expected);
        Log::debug('Expected Signature (with \n): ' . $expected_with_lf);
        Log::debug('Expected Signature (with \r\n): ' . $expected_with_crlf);
        Log::debug('Received Signature: ' . $sig);
        Log::debug('Raw Body: ' . $raw);
// --- END NEW DEBUGGING ---

// Check against all possibilities
        if ($sig === '' || $secret === '' || (!hash_equals($expected, $sig) && !hash_equals($expected_with_lf, $sig) && !hash_equals($expected_with_crlf, $sig))) {
            return response()->json(['error' => 'invalid signature'], 403);
        }

// ... success code
    }
}
