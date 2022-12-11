<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class EpisodeParsing
{
    public function episodeParsing(int $idTV, int $seasonNumber, int $episodeNumber): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/tv/'.$idTV.'/season/'.$seasonNumber.'/episode/'.$episodeNumber.'?api_key='.$apiKey.'&language=fr-FR');
        $item = $response->toArray();

        $response2 = $client->request('GET', 'https://api.themoviedb.org/3/tv/'.$idTV.'/season/'.$seasonNumber.'?api_key='.$apiKey.'&language=fr-FR');
        $item2 = $response2->toArray();

        $overview = !empty($item['overview']) ? $item['overview'] : "Aucune description";

        $episode = array(
            'id' => $idTV.'-'.$seasonNumber.'-'.$episodeNumber,
            'name' => $item['name'],
            'poster_path' => 'https://image.tmdb.org/t/p/original/' . $item2['poster_path'],
            'season_number' => $item['season_number'],
            'episode_number' => $item['episode_number'],
            'air_date' => $item['air_date'],
            'overview' => $overview,
            'type' => 'episode');

        return $episode;
    }}