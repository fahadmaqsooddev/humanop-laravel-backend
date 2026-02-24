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

    public function syncContactWithTags($user, $tag): bool
    {
        try {

            $email = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');

            if (empty($email)) {

                Log::warning('GHL Sync: Email is missing', compact('user'));

                return false;

            }

            $contactId = $this->getContactByEmail($email);

            if (empty($contactId)) {

                $created = $this->createUser($user);

                $contactId = $created['contact']['id'] ?? null;

                if (empty($contactId)) {

                    Log::error('GHL Sync: Failed to create contact', compact('email'));

                    return false;

                }

            }

            $contact = $this->getContact($contactId);

            if (!isset($contact['contact'])) {

                Log::error('GHL Sync: Contact not found after resolve', compact('contactId'));

                return false;

            }

            $existingTags = $contact['contact']['tags'] ?? [];



            if (in_array($tag, $existingTags, strict: true)) {

                return true;

            }

            $existingTags[] = $tag;

            $this->client->put("contacts/{$contactId}", [
                'headers' => $this->getHeaders(),
                'json' => ['tags' => array_values($existingTags)],
            ]);

            return true;

        } catch (\Exception $e) {

            Log::error('GHL Sync Contact Error', [
                'email' => $email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;

        }

    }

    private function getHeaders(): array
    {

        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Version' => '2021-07-28',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

    }

}
