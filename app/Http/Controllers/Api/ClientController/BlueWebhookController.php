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

        // 1) Read raw body and header
        $raw    = file_get_contents('php://input');                 // exact bytes as received
        $sig    = strtolower(trim($request->header('x-signature') ?? '')); // Blue sends this
        $secret = (string) config('services.blue.webhook_secret');  // from .env

        Log::info(print_r($raw, true));
        Log::info(print_r($sig, true));
        Log::info(print_r($secret, true));
        // 2) Make body look exactly like sender’s JSON string (JSON.stringify)
        $minified = $this->minifyJson($raw) ?? $raw;

        Log::info(print_r($minified, true));
    }

    private function minifyJson(string $raw): ?string
    {
        try {
            $arr = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            // JSON.stringify-like (no spaces/newlines; keep slashes & unicode)
            return json_encode($arr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return null; // not valid JSON; let caller use $raw fallback
        }
    }


}
