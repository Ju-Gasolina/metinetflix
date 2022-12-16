<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\Serie;
use Symfony\Component\HttpClient\HttpClient;
use function PHPUnit\Framework\isEmpty;

class SerieParsing
{
    public function popularParsing(int $page): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/tv/popular?api_key='.$apiKey.'&language=fr-FR&page='.$page);
        $items = $response->toArray();

        $series = array();
        foreach ($items['results'] as $item) {
            $card = new Card(
                $item['id'],
                $item['name'],
                $item['first_air_date'],
                'https://image.tmdb.org/t/p/original' . $item['poster_path'],
                'app_serie_show',
                'serie');
            $series[] = $card;
        }

        return $series;
    }

    public function onTheAirParsing(int $page): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/tv/on_the_air?api_key='.$apiKey.'&language=fr-FR&page='.$page);
        $items = $response->toArray();

        $series = array();
        foreach($items['results'] as $item) {
            $card = new Card(
                $item['id'],
                $item['name'],
                $item['first_air_date'],
                'https://image.tmdb.org/t/p/original' . $item['poster_path'],
                'app_serie_show',
                'serie');
            $series[] = $card;
        }

        return $series;
    }

    public function serieParsing(int $id): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/tv/'.$id.'?api_key='.$apiKey.'&language=fr-FR');
        $item = $response->toArray();

        $overview = !empty($item['overview']) ? $item['overview'] : "Aucune description";

        $genres = array();
        foreach($item['genres'] as $genre) {
            array_push($genres, $genre['name']);
        }
        if(count($genres) == 0) array_push($genres, "Aucun genre");

        $creators = array();
        foreach($item['created_by'] as $creator) {
            array_push($creators, $creator['name']);
        }
        if(count($creators) == 0) array_push($genres, "Aucun créateur");

        $seasons = array();
        foreach($item['seasons'] as $season) {
            $card = new Card(
                $item['id'].'-'.$season['season_number'],
                $season['name'],
                $season['air_date'] ?? "",
                'https://image.tmdb.org/t/p/original' . $season['poster_path'],
                'app_season_show',
                'season');
            $seasons[] = $card;
        }

        $serie = array(
            'id' => $item['id'],
            'name' => $item['name'],
            'backdrop_path' => 'https://image.tmdb.org/t/p/original' . $item['backdrop_path'],
            'poster_path' => 'https://image.tmdb.org/t/p/original' . $item['poster_path'],
            'original_name' => $item['original_name'],
            'original_language' => $item['original_language'],
            'first_air_date' => $item['first_air_date'],
            'last_air_date' => $item['last_air_date'],
            'number_of_seasons' => $item['number_of_seasons'],
            'number_of_episodes' => $item['number_of_episodes'],
            'overview' => $overview,
            'genres' => $genres,
            'creators' => $creators,
            'seasons' => $seasons,
            'type' => 'serie');

        return $serie;
    }

    public function serieCardParsing(Serie $serie): Card
    {
        $card = new Card(
            $serie->getIdTMDB(),
            $serie->getName(),
            $serie->getFirstAirDate(),
            $serie->getPosterPath(),
            'app_serie_show',
            'serie'
        );

        return $card;
    }

    function queryParsing(int $page, String $query): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/search/tv?api_key='.$apiKey.'&language=fr-FR&page='.$page.'&query='.$query.'&include_adult=false');
        $items = $response->toArray();


        $series = array();
        foreach($items['results'] as $item) {
            $card = new Card(
                $item['id'],
                $item['name'],
                $item['first_air_date'],
                'https://image.tmdb.org/t/p/original' . $item['poster_path'],
                'app_serie_show',
                'serie');
            $series[] = $card;
        }

        return $series;
    }

    public function sortParsing(int $page, String $sortBy): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client
            ->request(
                'GET',
                'https://api.themoviedb.org/3/discover/tv?api_key='.$apiKey.'&language=fr-FR&sort_by='.$sortBy.'&page='.$page.'&with_watch_monetization_types=flatrate&with_status=0&with_type=0'
            );
        $items = $response->toArray();

        $series = array();
        foreach ($items['results'] as $item) {
            $card = new Card(
                $item['id'],
                $item['name'],
                $item['first_air_date'],
                'https://image.tmdb.org/t/p/original' . $item['poster_path'],
                'app_serie_show',
                'serie');
            $series[] = $card;
        }

        return $series;
    }

    public function queryMaker(int $page, array $filters=[], String $sortBy = null):array
    {

        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';
        $query =  'https://api.themoviedb.org/3/discover/tv?api_key='.$apiKey.'&language=fr-FR';
        $keysFilters = array_keys($filters);
        if(isEmpty($filters) && !is_null($sortBy) ){

            $query .= '&sort_by=' . $this->formateSortBy($sortBy);
            foreach ($keysFilters as $keyFilter){

                if(gettype($filters[$keyFilter]) === 'object'){
                    $query .= '&'.$keyFilter.'='.$filters[$keyFilter]->format('Y-m-d');
                }else if($keyFilter === 'include_adult'){
                    $query .= '&'.$keyFilter.'=';
                    $query .= $filters[$keyFilter] ? 'true' : 'false';
                }

            }
        }else if(is_null($filters)){
            $query .= '&sort_by=' . $this->formateSortBy($sortBy);
        }



        $client = HttpClient::create();
        $response = $client
            ->request(
                'GET',
                $query
            );
        $items = $response->toArray();

        $series = array();
        foreach ($items['results'] as $item) {
            $card = new Card(
                $item['id'],
                $item['name'],
                $item['first_air_date'],
                'https://image.tmdb.org/t/p/original' . $item['poster_path'],
                'app_serie_show',
                'serie');
            $series[] = $card;
        }

        return $series;
    }


    function formateSortBy(string $sortBy): string
    {
        if($sortBy === 'date.asc'){
            return 'first_air_date.asc';
        }elseif($sortBy === 'date.desc'){
            return 'first_air_date.desc';
        }
        return $sortBy;

    }
}
