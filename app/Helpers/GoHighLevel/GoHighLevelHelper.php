<?php

namespace App\Helpers\GoHighLevel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GoHighLevelHelper
{

    public static function createUserInGoHighLevel($getUser = null)
    {
        try {

            $token = 'pit-0c93a962-87be-4904-bb16-80b04278f5c4';
            $locationId = 'tGZlNlIBZShcfUWMETK6';

            $client = new Client([
                'base_uri' => 'https://services.leadconnectorhq.com/',
                'timeout' => 30,
            ]);

            // Support array or object
            $firstName = is_array($getUser) ? ($getUser['first_name'] ?? '') : ($getUser->first_name ?? '');
            $lastName = is_array($getUser) ? ($getUser['last_name'] ?? '') : ($getUser->last_name ?? '');
            $email = is_array($getUser) ? ($getUser['email'] ?? '') : ($getUser->email ?? '');
            $phone = is_array($getUser) ? ($getUser['phone'] ?? '') : ($getUser->phone ?? '');
            $dob = is_array($getUser) ? ($getUser['date_of_birth'] ?? null) : ($getUser->date_of_birth ?? null);

            $payload = [
                'locationId' => $locationId,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'phone' => $phone,
            ];

            // Optional Date of Birth
            if (!empty($dob)) {
                $payload['dateOfBirth'] = date('Y-m-d', strtotime($dob));
            }

            $response = $client->post('contacts/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Version' => '2021-07-28',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {

            Log::error('GoHighLevel API Error', [
                'message' => $e->getMessage(),
                'response' => $e->hasResponse()
                    ? json_decode($e->getResponse()->getBody(), true)
                    : null
            ]);

            return false;
        }
    }

}
