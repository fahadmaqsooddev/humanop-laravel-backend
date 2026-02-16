<?php

namespace App\Services;

use App\Enums\Admin\Admin;
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

    public function getContactByEmail($email)
    {
        try {

            $response = $this->client->get('contacts/search/duplicate', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Version' => '2021-07-28',
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'locationId' => $this->locationId,
                    'email' => $email,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['contact']['id'] ?? null;

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            Log::error('Get Contact By Email Failed', [
                'error' => $e->getResponse()->getBody()->getContents()
            ]);

            return null;
        }
    }

    public function getContact($contactId)
    {
        try {

            $response = $this->client->get("contacts/{$contactId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Version' => '2021-07-28',
                    'Accept' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            Log::error('Get Contact Failed', [
                'error' => $e->getResponse()->getBody()->getContents()
            ]);

            return null;
        }
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

                "tags" => [Admin::NEW_USER]
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

    public function syncContactWithTags($user, $tag)
    {
        try {

            $email = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');

            $contactId = $this->getContactByEmail($email);

            // ==================================================
            // CONTACT EXISTS → UPDATE TAGS
            // ==================================================
            if (!empty($contactId)) {

                $contact = $this->getContact($contactId);

                $existingTags = $contact['contact']['tags'] ?? [];

                if (!in_array($tag, $existingTags)) {

                    $existingTags[] = $tag;

                }

                $this->client->put("contacts/{$contactId}", [

                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                        'Version' => '2021-07-28',
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],

                    'json' => [
                        'tags' => array_values($existingTags)
                    ],

                ]);

                return true;

            }

            // ==================================================
            // CONTACT NOT EXISTS → CREATE
            // ==================================================

            return $this->createUser($user);

        } catch (\Exception $e) {

            Log::error('GHL Sync Contact Error', [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

}
