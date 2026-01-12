<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeoService
{
    /**
     * Get location info by IP
     *
     * @param string|null $ip
     * @return array
     */
    public function getLocationByIp(?string $ip = null): array
    {
        // Step 1: Detect IP if not provided
        if (!$ip) {
            try {
                // Use IP fetch URL from config
                $ip = Http::timeout(2)
                    ->get(config('services.ipify.base_url'))
                    ->body();
            } catch (\Throwable $e) {
                Log::warning('Public IP fetch failed, using local IP', [
                    'error' => $e->getMessage(),
                ]);

                // Local IP fallback
                $ip = gethostbyname(gethostname());
                if (!$ip || in_array($ip, ['127.0.0.1', '::1'])) {
                    $ip = '8.8.8.8'; // fallback test IP
                }
            }
        }

        // Default response
        $default = [
            'ip'      => $ip,
            'city'    => null,
            'state'   => null,
            'country' => null,
        ];

        // Step 2: Fetch location from IP API (config URL)
        try {
            $response = Http::timeout(3)
                ->get(config('services.ip_api.base_url') . $ip);

            if (!$response->successful()) {
                Log::warning('Geo API failed', [
                    'ip'     => $ip,
                    'status' => $response->status(),
                ]);
                return $default;
            }

            $data = $response->json();

            return [
                'ip'      => $ip,
                'city'    => Str::limit((string)($data['city'] ?? ''), 50),
                'state'   => Str::limit((string)($data['regionName'] ?? ''), 50),
                'country' => Str::limit((string)($data['country'] ?? ''), 50),
            ];
        } catch (\Throwable $e) {
            Log::error('Geo API exception', [
                'ip'    => $ip,
                'error' => $e->getMessage(),
            ]);
            return $default;
        }
    }
}
