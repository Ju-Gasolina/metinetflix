<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class SerieParsing
{
    public function popularParsing(string $page): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/tv/popular/?api_key=' . $apiKey . '&page='.$page);
        $items = $response->toArray();

        $series = array();
        foreach ($items['results'] as $item) {
            $series[] = array(
                'name' => $item['name'],
                'first_air_date' => $item['first_air_date'],
                'poster_path' => 'https://image.tmdb.org/t/p/original/' . $item['poster_path']);
        }

        return $series;
    }
}