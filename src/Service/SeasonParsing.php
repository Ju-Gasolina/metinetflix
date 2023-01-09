<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\WatchlistCard;
use App\Entity\Season;
use Symfony\Component\HttpClient\HttpClient;

class SeasonParsing
{
    public function seasonParsing(int $idTV, int $seasonNumber): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/tv/'.$idTV.'/season/'.$seasonNumber.'?api_key='.$apiKey.'&language=fr-FR');
        $item = $response->toArray();

        $overview = !empty($item['overview']) ? $item['overview'] : "Aucune description";

        $episodes = array();
        foreach($item['episodes'] as $episode) {
            $card = new Card(
                $idTV.'-'.$seasonNumber.'-'.$episode['episode_number'],
                $episode['name'],
                $episode['air_date'] ?? "",
                'https://image.tmdb.org/t/p/original' . $item['poster_path'],
                'app_episode_show',
                'episode',
                null,
                $episode['vote_average']);
            $episodes[] = $card;
        }

        $response2 = $client->request('GET', 'https://api.themoviedb.org/3/tv/' . $idTV . '?api_key=' . $apiKey . '&language=fr-FR');
        $item2 = $response2->toArray();

        $season = array(
            'id' => $idTV.'-'.$seasonNumber,
            'name' => $item['name'],
            'poster_path' => 'https://image.tmdb.org/t/p/original' . $item['poster_path'],
            'serie_id' => $item2["id"],
            'serie' => $item2["name"],
            'season_number' => $item['season_number'],
            'air_date' => $item['air_date'],
            'number_of_episodes' => count($item['episodes']),
            'overview' => $overview,
            'episodes' => $episodes,
            'type' => 'season');

        return $season;
    }

    public function seasonWatchlistCardParsing(String $id, Season $season): WatchlistCard
    {
        $watchlistCard = new WatchlistCard(
            $id,
            $season->getIdTMDB(),
            $season->getName(),
            $season->getAirDate(),
            $season->getPosterPath(),
            'app_season_show',
            'season',
            null
        );

        return $watchlistCard;
    }
}