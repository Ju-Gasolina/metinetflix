<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\WatchlistItemRepository;

use App\Service\MovieParsing;
use App\Service\SerieParsing;
use App\Service\SeasonParsing;
use App\Service\EpisodeParsing;
use App\Service\SagaParsing;

#[Route('/watchlist')]
class WatchlistController extends AbstractController
{
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
                    $card = $movieParsing->movieCardParsing($item->getMovie());
                    break;

                case 'serie':
                    $card = $serieParsing->serieCardParsing($item->getSerie());
                    break;

                case 'season':
                    $card = $seasonParsing->seasonCardParsing($item->getSeason());
                    break;

                case 'episode':
                    $card = $episodeParsing->episodeCardParsing($item->getEpisode());
                    break;

                case 'saga':
                    $card = $sagaParsing->sagaCardParsing($item->getSaga());
                    break;
            }

            $watchlistItems[] = $card;
        }

        return $this->render('watchlist/show.html.twig', [
            'watchlistItems' => $watchlistItems,
        ]);
    }
}
