<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Feedback\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlueWebhookController extends Controller
{
    // In your controller method:
    public function ticketUpdated(Request $request)
    {
        // raw bytes exactly as received
        $raw = file_get_contents('php://input');

        // Build a "minified" JSON version (like JSON.stringify) if body is JSON
        $minified = $raw;
        if (str_starts_with(strtolower($request->header('content-type') ?? ''), 'application/json')) {
            try {
                $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
                // json_encode without pretty-print → compact/minified
                $minified = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                // if parsing fails, keep $minified = $raw
            }
        }

        $sigHeader = $request->header('x-signature') ?? '';
        $secretEnv = config('services.blue.webhook_secret');
        $method    = $request->getMethod();                 // e.g. POST
        $path      = '/'.$request->path();                  // e.g. /api/blue/webhook/ticket-updated

        if (! $this->isValidSignatureEx($sigHeader, $secretEnv, [
            $raw,                  // 1) exact raw body
            $minified,             // 2) minified JSON body
            $method."\n".$path."\n".$raw,        // 3) method + path + raw
            $method."\n".$path."\n".$minified,   // 4) method + path + minified
            $path."\n".$raw,                      // 5) path + raw
            $path."\n".$minified,                 // 6) path + minified
            str_replace("\r\n", "\n", $raw),      // 7) normalized newlines
            str_replace("\r\n", "\n", $minified), // 8) normalized newlines (minified)
        ])) {
            Log::warning('Blue webhook invalid signature', [
                'header'      => $sigHeader,
                'len_raw'     => strlen($raw),
                'len_min'     => strlen($minified ?? ''),
                'method'      => $method,
                'path'        => $path,
            ]);
            return response()->json(['error' => 'invalid signature'], 403);
        }

        // ... proceed to parse JSON & update ticket ...
    }

    /**
     * Try multiple candidate strings-to-sign, accept hex/base64 signatures,
     * and try both ASCII and HEX-decoded forms of the secret.
     */
    protected function isValidSignatureEx(string $sigHeader, string $secretEnv, array $candidates): bool
    {
        if ($sigHeader === '' || $secretEnv === '') return false;

        // Normalize provided signature (strip quotes, spaces; allow "sha256=<hex>")
        $provided = trim($sigHeader, " \t\n\r\0\x0B\"'");
        if (str_starts_with(strtolower($provided), 'sha256=')) {
            $provided = substr($provided, 7);
        }
        $providedLower = strtolower($provided);

        // Build candidate keys
        $keys = [$secretEnv];
        if (ctype_xdigit($secretEnv) && (strlen($secretEnv) % 2 === 0)) {
            $keys[] = hex2bin($secretEnv); // raw bytes from hex
        }

        foreach ($candidates as $toSign) {
            if (!is_string($toSign)) continue;

            foreach ($keys as $key) {
                // HMAC-SHA256 (raw)
                $rawMac = hash_hmac('sha256', $toSign, $key, true);
                $hexMac = bin2hex($rawMac);
                $b64Mac = base64_encode($rawMac);

                if (hash_equals($hexMac, $providedLower) || hash_equals($b64Mac, $provided)) {
                    return true;
                }
            }

            // Last-resort fallback (rare providers): SHA256(secret + payload) hex
            // (Concatenate bytes: secret-as-is then payload)
            foreach ($keys as $key) {
                $concat = (is_string($key) ? $key : '') . $toSign;
                $hexSha = hash('sha256', $concat);
                if (hash_equals($hexSha, $providedLower)) {
                    return true;
                }
            }
        }

        return false;
    }

}
