<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;

class IMDBService
{

    /**
     * @return array
     */
    public function parseIMDB(array $IMDBData)
    {
        $source = $IMDBData['results'] ?? $IMDBData['items'];
        $albums = [];
        foreach ($source as $album) {
            $albums[] = [
                'id' => $album['id'],
                'title' => $album['title'],
                'image' => $album['image'],
            ];
        }
        return $albums;
    }

    /**
     * @param string $input
     * @return mixed
     * @throws GuzzleException
     */
    public function search(string $input): mixed
    {
        $client = new Client();
        $response = $client->request('GET',
            'https://imdb-api.com/en/API/Search/' . env('API_IMDB_KEY') . '/' . $input
        );
        if ($response->getStatusCode() !== Response::HTTP_OK) return false;
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function random(): mixed
    {
        $client = new Client();
        $response = $client->request('GET',
            'https://imdb-api.com/en/API/MostPopularMovies/' . env('API_IMDB_KEY')
        );
        if ($response->getStatusCode() !== Response::HTTP_OK) return false;
        return json_decode($response->getBody()->getContents(), true);
    }
}
