<?php

namespace App\Services;

use App\Models\Source;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SpotifyService
{
    /**
     * @param array $spotifyData
     * @return array
     */
    public function parseSpotify(array $spotifyData, bool $feed = false): array
    {
        $albums = [];
        foreach ($spotifyData['albums']['items'] as $album) {
            $add = [
                'id' => $album['id'],
                'name' => $album['name'],
                'image' => $album['images'][0]['url'],
            ];
            if ($feed) $add['source'] = 'spotify';
            $albums[] = $add;
        }
        return $albums;
    }

    /**
     * @param string $input
     * @param int|null $offset
     * @return mixed
     * @throws GuzzleException
     */
    public function search(string $input, int|null $offset = 0): mixed
    {
        $uri = 'https://api.spotify.com/v1/search?q="' . $input . '"' . '&type=album&offset=' . ($offset ?? 0);
        return $this->spotifySecurity($uri);
    }

    /**
     * @param int|null $offset
     * @return mixed
     * @throws GuzzleException
     */
    public function random(int|null $offset = 0): mixed
    {
        $uri = 'https://api.spotify.com/v1/browse/new-releases?country=FR&limit=50&offset=' . ($offset * 50 ?? 0);
        return $this->spotifySecurity($uri);
    }

    /**
     * @param string $uri
     * @return false|mixed
     * @throws GuzzleException
     */
    private function spotifySecurity(string $uri): mixed
    {
        $databaseToken = Source::where('name', 'spotify')?->first();
        $token = $databaseToken['token'] ?? $this->Authenticate();
        if (!$token) return false;
        try {
            $response = $this->spotifyRequest($uri, $token);
        } catch (Exception) {
            try {
                $token = $this->Authenticate();
                $response = $this->spotifyRequest($uri, $token);
            } catch (Exception) {
                return false;
            }
        }
        if ($response->getStatusCode() !== ResponseAlias::HTTP_OK) return false;
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $uri
     * @param string $token
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function spotifyRequest(string $uri, string $token): ResponseInterface
    {
        $client = new Client();
        return $client->request('GET', $uri,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    private function Authenticate(): mixed
    {
        try {
            $client = new Client();
            $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'auth' => [env('API_SPOTIFY_USERNAME'), env('API_SPOTIFY_PASSWORD')],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);
            $token = json_decode($response->getBody()->getContents(), true);
            $source = Source::where('name', 'spotify')?->first();
            $source->token = $token['access_token'];
            $source->save();
            return $token['access_token'];
        } catch (Exception) {
            return false;
        }
    }
}
