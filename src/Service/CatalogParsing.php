<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use function PHPUnit\Framework\isEmpty;

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


    public function sortParsing(int $page, string $sortBy)
    {
        $serieParsing = new SerieParsing();
        $movieParsing = new MovieParsing();


        if($sortBy !== 'none') {
            if ($sortBy === 'date.asc') {
                $arrayMovies = $movieParsing->sortParsing($page, 'release_date.asc');
                $arraySeries = $serieParsing->sortParsing($page, 'first_air_date.asc');
            } else if ($sortBy === 'date.desc') {
                $arrayMovies = $movieParsing->sortParsing($page, 'release_date.desc');
                $arraySeries = $serieParsing->sortParsing($page, 'first_air_date.desc');
            } else {
                $arrayMovies = $movieParsing->sortParsing($page, $sortBy);
                $arraySeries = $serieParsing->sortParsing($page, $sortBy);
            }
        }else {
            $arrayMovies = $movieParsing->popularParsing($page);
            $arraySeries = $serieParsing->popularParsing($page);
        }


        return array_merge($arrayMovies, $arraySeries);


    }

    public function filtersParsing(int $page, array $filtersList)
    {
        $serieParsing = new SerieParsing();
        $movieParsing = new MovieParsing();

        //TODO Format de la date dans l'api : 1920-03-11
        if(isEmpty($filtersList)) {

        }else {
            $arrayMovies = $movieParsing->popularParsing($page);
            $arraySeries = $serieParsing->popularParsing($page);
        }


        return array_merge($arrayMovies, $arraySeries);


    }

    public function queryMaker(int $page, array $filters = [] ,string $sortBy=null ){
        $serieParsing = new SerieParsing();
        $movieParsing = new MovieParsing();

        $arrayMovies = $movieParsing->queryMaker($page,$filters,$sortBy);
        $arraySeries = $serieParsing->queryMaker($page,$filters,$sortBy);


        return array_merge($arrayMovies, $arraySeries);

    }



}