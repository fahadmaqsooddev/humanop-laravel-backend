<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Feedback\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ParagonIE\Sodium\Core\Curve25519\Fe;
use Symfony\Component\HttpFoundation\Response;

class BlueWebhookController extends Controller
{
    // app/Http/Controllers/Api/BlueWebhookController.php

    public function ticketUpdated(Request $request)
    {
        // 1) exact raw body
        $payload = file_get_contents('php://input');

        // 2) header name from Blue
        $sigHeader = $request->header('x-signature') ?? ''; // <-- important!

        // 3) your .env secret (hex-looking)
        $secretEnv = config('services.blue.webhook_secret'); // e.g. 6760d8bb...

        if (! $this->isValidSignature($payload, $sigHeader, $secretEnv)) {
            Log::warning('Blue webhook invalid signature', [
                'header'      => $sigHeader,
                'len_payload' => strlen($payload),
            ]);
            return response()->json(['error' => 'invalid signature'], 403);
        }

        return \response()->json('true',200);

        // ... proceed: read JSON and update ticket as before
    }

    protected function isValidSignature(string $payload, string $sigHeader, string $secretEnv): bool
    {
        if ($sigHeader === '' || $secretEnv === '') return false;

        // Normalize provided signature (Blue sends lowercase hex)
        $provided = trim($sigHeader, " \t\n\r\0\x0B\"'");
        if (str_starts_with(strtolower($provided), 'sha256=')) {
            $provided = substr($provided, 7);
        }
        $provided = strtolower($provided);

        // Build candidate keys:
        // - ASCII secret (in case Blue expects it as-is)
        // - hex-decoded secret (very common)
        $keys = [$secretEnv];
        if (ctype_xdigit($secretEnv) && (strlen($secretEnv) % 2 === 0)) {
            $keys[] = hex2bin($secretEnv);
        }

        // Compute HMAC for each key; accept hex or base64 signatures
        foreach ($keys as $key) {
            $raw = hash_hmac('sha256', $payload, $key, true);
            $hex = bin2hex($raw);
            $b64 = base64_encode($raw);

            if (hash_equals($hex, $provided) || hash_equals($b64, $provided)) {
                return true;
            }
        }

        return false;
    }

}
