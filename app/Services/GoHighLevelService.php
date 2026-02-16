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

            $data = json_decode($response->getBody(), true);

            return $data['contact']['id'] ?? null;

        } catch (\Exception $e) {
            return null;
        }
    }

    public function getContact($contactId)
    {
        $response = $this->client->get("contacts/{$contactId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Version' => '2021-07-28',
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
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

    public function updateContactTags($email, $newTag)
    {
        try {

            // 1️⃣ Get Contact ID
            $contactId = $this->getContactByEmail($email);

            if (!$contactId) {
                return "Contact not found";
            }

            // 2️⃣ Get existing tags
            $contact = $this->getContact($contactId);
            $existingTags = $contact['contact']['tags'] ?? [];

            // 3️⃣ Merge tags (avoid duplicates)
            if (!in_array($newTag, $existingTags)) {
                $existingTags[] = $newTag;
            }

            // 4️⃣ Update contact
            $response = $this->client->put("contacts/{$contactId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Version' => '2021-07-28',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'tags' => $existingTags
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            Log::error('GHL Update Tag Error', [
                'error' => $e->getResponse()?->getBody()?->getContents()
            ]);

            return false;
        }
    }

}
