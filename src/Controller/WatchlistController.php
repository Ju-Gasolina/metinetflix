<?php

namespace App\Controller;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\WatchlistItemRepository;

use App\Service\MovieParsing;
use App\Service\SerieParsing;
use App\Service\SeasonParsing;
use App\Service\EpisodeParsing;
use App\Service\SagaParsing;
use Symfony\Component\Security\Core\Security;

#[Route('/watchlist')]
class WatchlistController extends AbstractController
{
    #[NoReturn] #[Route('/', name: 'app_watchlist_index', methods: ['GET'])]
    public function index(Security $security){
        $user = $security->getUser();
        dd($user);
    }

    #[Route('/{id}', name: 'app_watchlist_show', methods: ['GET'])]
    public function show(Int $id,
                         WatchlistItemRepository $watchlistItemRepository,
                         MovieParsing $movieParsing,
                         SerieParsing $serieParsing,
                         SeasonParsing $seasonParsing,
                         EpisodeParsing $episodeParsing,
                         SagaParsing $sagaParsing
    ): Response
    {
        $items = $watchlistItemRepository->findBy([
                "watchlist" => $id]);

        $watchlistItems = array();
        foreach($items as $item)
        {
            switch($item->getItemType())
            {
                case 'movie':
                    $watchlistCard = $movieParsing->movieWatchlistCardParsing($item->getId(), $item->getMovie());
                    break;

                case 'serie':
                    $watchlistCard = $serieParsing->serieWatchlistCardParsing($item->getId(),$item->getSerie());
                    break;

                case 'season':
                    $watchlistCard = $seasonParsing->seasonWatchlistCardParsing($item->getId(),$item->getSeason());
                    break;

                case 'episode':
                    $watchlistCard = $episodeParsing->episodeWatchlistCardParsing($item->getId(),$item->getEpisode());
                    break;

                case 'saga':
                    $watchlistCard = $sagaParsing->sagaWatchlistCardParsing($item->getId(),$item->getSaga());
                    break;
            }

            $watchlistItems[] = $watchlistCard;
        }

        return $this->render('watchlist/show.html.twig', [
            'watchlistItems' => $watchlistItems,
        ]);
    }
}
