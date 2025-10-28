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
        $raw = file_get_contents('php://input');                   // exact bytes sent by Blue
        $sig = strtolower(trim($request->header('x-signature') ?? ''));
        $secret = (string)config('services.blue.webhook_secret');    // same string as Blue

        // HMAC-SHA256 over the RAW body (must match the exact body Blue signed)
        $expected = bin2hex(hash_hmac('sha256', $raw, $secret, true));

        if ($sig === '' || $secret === '' || !hash_equals($expected, $sig)) {
            return response()->json(['error' => 'invalid signature'], 403);
        }
    }
        // … handle the payload safely …

}
