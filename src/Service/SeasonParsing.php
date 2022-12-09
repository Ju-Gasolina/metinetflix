<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class SeasonParsing
{
    public function seasonParsing(int $idTV, int $idTVSeason): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/tv/'.$idTV.'/season/'.$idTVSeason.'?api_key=' . $apiKey . '&language=fr-FR');
        $item = $response->toArray();

        $overview = !empty($item['overview']) ? $item['overview'] : "Aucune description";

        $season = array(
            'id' => $item['id'],
            'name' => $item['name'],
            'poster_path' => 'https://image.tmdb.org/t/p/original/' . $item['poster_path'],
            'season_number' => $item['season_number'],
            'air_date' => $item['air_date'],
            'number_of_episodes' => count($item['episodes']),
            'overview' => $overview);

        return $season;
    }
}