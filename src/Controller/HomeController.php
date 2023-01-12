<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\SerieRepository;
use App\Repository\WatchlistItemRepository;
use App\Repository\WatchlistRepository;
use App\Service\CatalogParsing;
use App\Service\MovieParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CatalogParsing $catalogParsing, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, MovieRepository $movieRepository, SerieRepository $serieRepository): Response
    {
        $arrayItem = $catalogParsing->upcomingParsing(1);
        shuffle($arrayItem);

        foreach($arrayItem as $item) {
            if($item->getType() == "movie"){
                $item->setIsWatchlistItem($this->isWatchlistItemMovie($item->getId(), $security, $watchlistRepository, $watchlistItemRepository, $movieRepository));
            }
            else if ($item->getType() == "serie") {
                $item->setIsWatchlistItem($this->isWatchlistItemSerie($item->getId(), $security, $watchlistRepository, $watchlistItemRepository, $serieRepository));
            }
        }

        return $this->render('/index.html.twig', [
            'controller_name' => 'HomeController',
            'items' => array_slice($arrayItem, 0, 15),
        ]);
    }

    private function isWatchlistItemMovie(Int $idTMDB, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, MovieRepository $movieRepository): bool
    {
        if(!$this->isGranted('ROLE_USER')) return false;

        $searchMovie = $movieRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(isset($searchMovie))
        {
            $result = $watchlistItemRepository->findOneBy([
                "watchlist" => $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()]),
                "movie" => $searchMovie->getId()
            ]);

            if(isset($result)) return true;
            else return false;
        }
        else return false;
    }

    private function isWatchlistItemSerie(string $idTMDB, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, SerieRepository $serieRepository): bool
    {
        if(!$this->isGranted('ROLE_USER')) return false;

        $searchSerie = $serieRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(isset($searchSerie))
        {
            $result = $watchlistItemRepository->findOneBy([
                "watchlist" => $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()]),
                "serie" => $searchSerie->getId()
            ]);

            if(isset($result)) return true;
            else return false;
        }
        else return false;
    }
}
