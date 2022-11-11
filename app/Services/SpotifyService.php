<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;

class SpotifyService
{

    /**
     * @return array
     */
    public function parseSpotify(array $spotifyData)
    {
        $albums = [];
        foreach ($spotifyData['albums']['items'] as $album) {
            $albums[] = [
                'id' => $album['id'],
                'name' => $album['name'],
                'image' => $album['images'][0]['url'],
            ];
        }
        return $albums;
    }

    /**
     * @param string $input
     * @param int|null $offset
     * @return mixed
     * @throws GuzzleException
     */
    public function search(string $input, int|null $offset=0 ): mixed
    {
        $token = $this->Authenticate();
        if ($token) {
            $client = new Client();
            $response = $client->request('GET',
                'https://api.spotify.com/v1/search?q="' . $input . '"' .
                '&type=album&offset=' . ($offset ?? 0),
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Authorization' => 'Bearer ' . $token['access_token'],
                    ],
                ]
            );
            if ($response->getStatusCode() !== Response::HTTP_OK) return false;
            return json_decode($response->getBody()->getContents(), true);
        }
        return $token;
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function random(int|null $offset=0): mixed
    {
        $token = $this->Authenticate();
        if ($token) {
            $client = new Client();
            $response = $client->request('GET',
                'https://api.spotify.com/v1/browse/new-releases?country=FR&offset=' . ($offset ?? 0),
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token['access_token'],
                    ],
                ]
            );
            if ($response->getStatusCode() !== Response::HTTP_OK) return false;
            return json_decode($response->getBody()->getContents(), true);
        }
        return $token;
    }

    /**
     * @return false|mixed
     * @throws GuzzleException
     */
    private function Authenticate(): mixed
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
        if ($response->getStatusCode() !== Response::HTTP_OK) return false;
        return json_decode($response->getBody()->getContents(), true);
    }
}
