<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GoHighLevelService
{
    protected Client $client;
    protected string $token;
    protected string $locationId;

    public function __construct()
    {
        $this->client = new Client([

            'base_uri' => config('services.gohighlevel.base_uri'),

            'timeout' => 30,
        ]);

        $this->token = config('services.gohighlevel.token');

        $this->locationId = config('services.gohighlevel.location_id');
    }

    public function createUser($user)
    {
        try {

            $firstName = is_array($user) ? ($user['first_name'] ?? '') : ($user->first_name ?? '');

            $lastName = is_array($user) ? ($user['last_name'] ?? '') : ($user->last_name ?? '');

            $email = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');

            $phone = is_array($user) ? ($user['phone'] ?? '') : ($user->phone ?? '');

            $dob = is_array($user) ? ($user['date_of_birth'] ?? null) : ($user->date_of_birth ?? null);

            $payload = [

                'locationId' => $this->locationId,

                'firstName' => $firstName,

                'lastName' => $lastName,

                'email' => $email,

                'phone' => $phone,
            ];

            // Optional Date of Birth
            if (!empty($dob)) {

                $payload['dateOfBirth'] = date('Y-m-d', strtotime($dob));
            }

            $response = $this->client->post('contacts/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Version' => '2021-07-28',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {

            Log::error('GoHighLevel API Error', [/* log details */]);

            return false;
        }
    }
}
