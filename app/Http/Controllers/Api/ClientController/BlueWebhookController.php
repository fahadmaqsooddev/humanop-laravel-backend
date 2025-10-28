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
        // Use Laravel's method to get the raw body
        $raw = $request->getContent();

        // Get the signature header
        $sig = strtolower(trim($request->header('x-signature') ?? ''));

        // Get the secret
        $secret = (string)config('services.blue.webhook_secret');

        // Calculate expected signature
        $expected = bin2hex(hash_hmac('sha256', $raw, $secret, true));

        // --- TEMPORARY DEBUGGING ---
        // This will write to storage/logs/laravel.log
        Log::debug('--- Blue Webhook Check ---');
        Log::debug('Received Signature: ' . $sig);
        Log::debug('Expected Signature: ' . $expected);
        Log::debug('Secret Loaded: ' . (empty($secret) ? 'NO' : 'YES - Last 4: ' . substr($secret, -4)));
        Log::debug('Signatures Match: ' . ($sig === $expected ? 'YES' : 'NO'));
        Log::debug('Raw Body: ' . $raw); // Uncomment this if you need to see the body, but be careful if it contains sensitive data.
        // --- END DEBUGGING ---

        // Use hash_equals for secure, timing-attack-safe comparison
        if ($sig === '' || $secret === '' || !hash_equals($expected, $sig)) {
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // If it gets here, the signature is valid!
        Log::info('Blue Webhook Signature Validated Successfully.');

        // ... process your valid webhook ...

        return response()->json(['status' => 'success'], 200);
    }
}
