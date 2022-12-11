<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class CatalogParsing
{

    public function popularParsing(int $page): array
    {

        $serieParsing = new SerieParsing();
        $movieParsing = new MovieParsing();

        $arrayMovies = $movieParsing->popularParsing($page);
        $arraySeries = $serieParsing->popularParsing($page);

       return array_merge($arrayMovies, $arraySeries);
    }

    public function upcomingParsing(int $page): array
    {
        $serieParsing = new SerieParsing();
        $movieParsing = new MovieParsing();

        $arrayMovies = $movieParsing->upcomingParsing($page);
        $arraySeries = $serieParsing->onTheAirParsing($page);

        return array_merge($arrayMovies, $arraySeries);
    }

    public function queryParsing(int $page, string $query): array
    {
        $serieParsing = new SerieParsing();
        $movieParsing = new MovieParsing();

        $arrayMovies = $movieParsing->queryParsing($page, $query);
        $arraySeries = $serieParsing->queryParsing($page, $query);

        return array_merge($arrayMovies, $arraySeries);
    }
}