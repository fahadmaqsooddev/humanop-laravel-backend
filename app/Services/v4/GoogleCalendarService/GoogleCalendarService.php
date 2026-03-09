<?php

namespace App\Services\v4\GoogleCalendarService;
use Google\Client;

class GoogleCalendarService
{

    public function client(): Client
    {
        $client = new Client();

        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));

        $client->setAccessType('offline');
        $client->setPrompt('consent');

        $client->addScope([
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events'
        ]);

        return $client;
    }

    public function authUrl($userId)
    {
        $client = $this->client();

        $client->setState(encrypt($userId));

        return $client->createAuthUrl();
    }

    public function fetchToken($code)
    {
        $client = $this->client();

        return $client->fetchAccessTokenWithAuthCode($code);
    }

}
