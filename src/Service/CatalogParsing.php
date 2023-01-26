<?php

namespace App\Service;

use PhpParser\Node\Expr\Array_;
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

        $arrayCatalog = array_merge($movieParsing->queryParsing($page, $query), $serieParsing->queryParsing($page, $query));

        usort($arrayCatalog, function ($first, $second) {
            return strtolower($first->getTitle()) > strtolower($second->getTitle());
        });

        return $arrayCatalog;
    }


    public function filtersParsing(int $page, array $filtersList)
    {
        $serieParsing = new SerieParsing();
        $movieParsing = new MovieParsing();

        if (isEmpty($filtersList)) {

        } else {
            $arrayMovies = $movieParsing->popularParsing($page);
            $arraySeries = $serieParsing->popularParsing($page);
        }


        return array_merge($arrayMovies, $arraySeries);


    }

    public function queryMaker(int $page, array $options = [])
    {
        $serieParsing = new SerieParsing();
        $movieParsing = new MovieParsing();

        $arrayCatalog = array_merge($movieParsing->queryMaker($page, $options), $serieParsing->queryMaker($page, $options));
        return  $this->getArraySorted($options['sortBy'], $arrayCatalog);;

    }

    public function getArraySorted($sortBy, $arrayMovies){

        $sortedArray = $arrayMovies;
        switch ($sortBy){
            case 'vote_average.desc':
                usort($sortedArray, function ($first, $second) {
                    return strtolower($first->getMarkAverage()) < strtolower($second->getMarkAverage());
                });
                break;
            case 'vote_average.asc':

                usort($sortedArray, function ($first, $second) {
                    return strtolower($first->getMarkAverage()) > strtolower($second->getMarkAverage());
                });
                break;

            case 'popularity.asc':

                usort($sortedArray, function ($first, $second) {
                    return strtolower($first->getPopularity()) < strtolower($second->getPopularity());
                });
                break;

            case 'popularity.desc':

                usort($sortedArray, function ($first, $second) {
                    return strtolower($first->getPopularity()) > strtolower($second->getPopularity());
                });
                break;


            default:

                shuffle($sortedArray);
                break;

        }

        return $sortedArray;
    }


}