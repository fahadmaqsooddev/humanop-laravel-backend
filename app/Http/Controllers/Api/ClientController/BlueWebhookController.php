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
        $raw    = file_get_contents('php://input');
        $sig    = strtolower(trim($request->header('x-signature') ?? ''));
        $secret = (string) config('services.blue.webhook_secret');
        $method = $request->getMethod();
        $path   = '/'.$request->path();

        $min = $raw;
        try { $min = json_encode(json_decode($raw, true, 512, JSON_THROW_ON_ERROR), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); } catch (\Throwable $e) {}
        $normRaw = str_replace("\r\n", "\n", $raw);
        $normMin = str_replace("\r\n", "\n", $min);

// build candidate strings Blue might sign
        $cands = [
            ['name'=>'raw',                   's'=>$raw],
            ['name'=>'minified',              's'=>$min],
            ['name'=>'raw_nl',                's'=>$normRaw],
            ['name'=>'min_nl',                's'=>$normMin],
            ['name'=>'path+raw',              's'=>$path."\n".$raw],
            ['name'=>'path+min',              's'=>$path."\n".$min],
            ['name'=>'method+path+raw',       's'=>$method."\n".$path."\n".$raw],
            ['name'=>'method+path+min',       's'=>$method."\n".$path."\n".$min],
            ['name'=>'[object Object]',       's'=>'[object Object]'],
        ];

// possible keys (string & hex-decoded)
        $keys = [$secret];
        if (ctype_xdigit($secret) && strlen($secret) % 2 === 0) {
            $keys[] = hex2bin($secret);
        }
        $keys[] = ''; // try empty key (in case Blue has no secret applied)

// helpers
        $match = null; $mode = null; $exp = null;
        $eq = function($a,$b){ return hash_equals($a,$b); };

// Try HMAC-SHA256 full hex 64 chars
        foreach ($cands as $c) {
            foreach ($keys as $k) {
                $hex = bin2hex(hash_hmac('sha256', $c['s'], $k, true));
                if ($eq($hex, $sig)) { $match = $c['name']; $mode='hmac-sha256'; $exp=$hex; break 2; }
            }
        }
// Try HMAC-SHA256 truncated to 16 bytes (32 hex)
        if (!$match && strlen($sig) === 32) {
            foreach ($cands as $c) {
                foreach ($keys as $k) {
                    $hex = bin2hex(hash_hmac('sha256', $c['s'], $k, true));
                    if ($eq(substr($hex,0,32), $sig)) { $match = $c['name']; $mode='hmac-sha256-trunc16'; $exp=substr($hex,0,32); break 2; }
                }
            }
        }
// Try HMAC-MD5 (32 hex)
        if (!$match && strlen($sig) === 32) {
            foreach ($cands as $c) {
                foreach ($keys as $k) {
                    $hex = hash_hmac('md5', $c['s'], $k);
                    if ($eq($hex, $sig)) { $match = $c['name']; $mode='hmac-md5'; $exp=$hex; break 2; }
                }
            }
        }
// Try plain SHA256 (no HMAC) full or trunc16
        if (!$match) {
            foreach ($cands as $c) {
                $hex = hash('sha256', $c['s']);
                if ($eq($hex, $sig)) { $match = $c['name']; $mode='sha256'; $exp=$hex; break; }
                if (strlen($sig)===32 && $eq(substr($hex,0,32), $sig)) { $match = $c['name']; $mode='sha256-trunc16'; $exp=substr($hex,0,32); break; }
            }
        }
// Try plain MD5 (no HMAC)
        if (!$match && strlen($sig)===32) {
            foreach ($cands as $c) {
                $hex = md5($c['s']);
                if ($eq($hex, $sig)) { $match = $c['name']; $mode='md5'; $exp=$hex; break; }
            }
        }

        if (!$match) {
            Log::warning('Webhook sig unmatched', [
                'received'=>$sig,
                'len_payload'=>strlen($raw),
                'secret_len'=>strlen($secret),
            ]);
            return response()->json(['error'=>'invalid signature'], 403);
        }

        Log::info('Webhook signature matched', ['mode'=>$mode, 'signed'=>$match]);

    }
}
