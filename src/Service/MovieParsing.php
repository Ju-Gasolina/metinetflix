<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class MovieParsing
{
    public function popularParsing(int $page): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/popular?api_key='.$apiKey.'&language=fr-FR&page=1');
        $items = $response->toArray();

        $movies = array();
        foreach($items['results'] as $item) {
            $movies[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'release_date' => $item['release_date'],
                'poster_path' => 'https://image.tmdb.org/t/p/original/' . $item['poster_path']);
        }

        return $movies;
    }
}