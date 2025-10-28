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
        Log::info('e');
        $raw    = file_get_contents('php://input');
        $sig    = $request->header('x-signature') ?? '';
        $secret = config('services.blue.webhook_secret');

        // Build JSON.stringify-equivalent string
        $toSign = $raw;
        if (str_starts_with(strtolower($request->header('content-type') ?? ''), 'application/json')) {
            try {
                $toSign = json_encode(json_decode($raw, true, 512, JSON_THROW_ON_ERROR), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {}
        }

        if (! $this->verifyBlueSignature($sig, $toSign, $secret)) {
            Log::warning('Blue webhook invalid signature', ['header' => $sig, 'len_raw' => strlen($raw)]);
            return response()->json(['error' => 'invalid signature'], 403);
        }
    }

    protected function verifyBlueSignature(string $sigHeader, string $toSign, string $secret): bool
    {
        if ($sigHeader === '' || $secret === '') return false;

        $provided = strtolower(trim($sigHeader, " \t\n\r\0\x0B\"'"));
        $hex      = bin2hex(hash_hmac('sha256', $toSign, $secret, true));
        return hash_equals($hex, $provided);
    }


}
