<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SpotifyService
{

    /**
     * @return false|mixed
     * @throws GuzzleException
     */
    public function Authenticate(): mixed
    {
        $client = new Client();
        $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'auth' => ["2b12228eabf04131b6f4a00119aa4253", "2a0f0949ffc34816a52c3adeb113aa7d"],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);
        if ($response->getStatusCode() !== 200) return false;
        return json_decode($response->getBody()->getContents(), true);
    }

}
