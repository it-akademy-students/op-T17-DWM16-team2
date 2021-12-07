<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getMovieData($id): array
    {
        $response = $this->client->request(
            'GET',
            'https://imdb-api.com/fr/API/Title/k_3j0zj3u0/' . $id
        );
        return $response->toArray();
    }
}