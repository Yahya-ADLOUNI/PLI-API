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
    public function parseIMDB(array $IMDBData, bool $feed = false)
    {
        $source = $IMDBData['results'] ?? $IMDBData['items'];
        $albums = [];
        foreach ($source as $album) {
            $add = [
                'id' => $album['id'],
                'name' => $album['title'],
                'image' => $album['image'] ?? null,
            ];
            if ($feed) $add['source'] = 'imdb';
            $albums[] = $add;
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
            'https://imdb-api.com/en/API/SearchAll/' . env('API_IMDB_KEY') . '/' . $input
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
        $chance = random_int(1, 4);
        if ($chance === 1) $link = 'https://imdb-api.com/en/API/InTheaters/';
        else if ($chance === 2) $link = 'https://imdb-api.com/en/API/MostPopularMovies/';
        else if ($chance === 3) $link = 'https://imdb-api.com/en/API/Top250TVs/';
        else $link = 'https://imdb-api.com/en/API/BoxOffice/';
        $client = new Client();
        $response = $client->request('GET', $link . env('API_IMDB_KEY'));
        if ($response->getStatusCode() !== Response::HTTP_OK) return false;
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function latest(): mixed
    {
        $client = new Client();
        $response = $client->request('GET',
            'https://imdb-api.com/en/API/InTheaters/' . env('API_IMDB_KEY')
        );
        if ($response->getStatusCode() !== Response::HTTP_OK) return false;
        return json_decode($response->getBody()->getContents(), true);
    }
}
