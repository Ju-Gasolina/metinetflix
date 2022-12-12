<?php

namespace App\Service;


use App\Entity\Card;
use Symfony\Component\HttpClient\HttpClient;

class SagaParsing
{
    public function indexParsing(int $page): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/popular?api_key='.$apiKey.'&language=fr-FR&page='.$page);
        $items = $response->toArray();

        $sagas = array();
        foreach($items['results'] as $item) {
            $client2 = HttpClient::create();
            $response2 = $client2->request('GET', 'https://api.themoviedb.org/3/movie/'.$item['id'].'?api_key='.$apiKey.'&language=fr-FR&page='.$page);
            $item2 = $response2->toArray();

            if(isset($item2['belongs_to_collection']))
            {
                $card = new Card(
                    $item2['belongs_to_collection']['id'],
                    $item2['belongs_to_collection']['name'],
                    $item2['belongs_to_collection']['release_date'] ?? "",
                    'https://image.tmdb.org/t/p/original/' . $item2['belongs_to_collection']['poster_path'],
                    'app_saga_show',
                    'saga');
                $sagas[] = $card;
            }
        }

        return $sagas;
    }

    public function sagaParsing(int $id): array
    {
        $apiKey = '357ffc10ea12b3e3226406719d3f9fe5';

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/collection/'.$id.'?api_key='.$apiKey.'&language=fr-FR');
        $item = $response->toArray();

        $overview = !empty($item['overview']) ? $item['overview'] : "Aucune description";

        $movies = array();
        foreach($item['parts'] as $movie) {
            $card = new Card(
                $movie['id'],
                $movie['title'],
                $movie['release_date'] ?? "",
                'https://image.tmdb.org/t/p/original/' . $movie['poster_path'],
                'app_movie_show',
                'movie');
            $movies[] = $card;
        }

        $saga = array(
            'id' => $item['id'],
            'name' => $item['name'],
            'backdrop_path' => 'https://image.tmdb.org/t/p/original/' . $item['backdrop_path'],
            'overview' => $overview,
            'movies' => $movies,
            'type' => 'saga');

        return $saga;
    }
}