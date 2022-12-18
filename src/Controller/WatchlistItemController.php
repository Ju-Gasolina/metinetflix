<?php

namespace App\Controller;

use App\Entity\WatchlistItem;
use App\Repository\WatchlistItemRepository;
use App\Service\EpisodeParsing;
use App\Service\MovieParsing;
use App\Service\SagaParsing;
use App\Service\SeasonParsing;
use App\Service\SerieParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\WatchlistRepository;
use App\Repository\MovieRepository;
use App\Repository\SerieRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\SagaRepository;

#[Route('/watchlist/item')]
class WatchlistItemController extends AbstractController
{
    #[Route('/new/{type}/{idTMDB}', name: 'app_watchlist_item_new', methods: ['GET', 'POST'])]
    public function new(String $type, String $idTMDB): Response
    {
        if($type == "movie")
        {
            return $this->redirectToRoute('app_movie_new', ['idTMDB' => $idTMDB]);
        }
        else if($type == "serie")
        {
            return $this->redirectToRoute('app_serie_new', ['idTMDB' => $idTMDB]);
        }
        else if($type == "season")
        {
            return $this->redirectToRoute('app_season_new', ['idTMDB' => $idTMDB]);
        }
        else if($type == "episode")
        {
            return $this->redirectToRoute('app_episode_new', ['idTMDB' => $idTMDB]);
        }
        else if($type == "saga")
        {
            return $this->redirectToRoute('app_saga_new', ['idTMDB' => $idTMDB]);
        }
        else
        {
            return $this->redirect('/');
        }
    }

    #[Route('/add/{type}/{idEntity}', name: 'app_watchlist_item_add', methods: ['GET', 'POST'])]
    public function add(String $type,
                        Int $idEntity,
                        WatchlistRepository $watchlistRepository,
                        WatchlistItemRepository $watchlistItemRepository,
                        MovieRepository $movieRepository,
                        SerieRepository $serieRepository,
                        SeasonRepository $seasonRepository,
                        EpisodeRepository $episodeRepository,
                        SagaRepository $sagaRepository
    ): Response
    {
        //Watchlist find request
        $watchlist = $watchlistRepository->findOneBy(['id' => 1]);

        //WatchlistItem creation
        $watchlistItem = $watchlistItemRepository->findOneBy([$type => $idEntity]);

        if(!isset($watchlistItem)) {
            $watchlistItem = new WatchlistItem();
            $watchlistItem->setWatchlist($watchlist);
            $watchlistItem->setItemType($type);

            if($type == "movie") {
                $movie = $movieRepository->find($idEntity);
                $watchlistItem->setMovie($movie);
            }
            else if($type == "serie")
            {
                $serie = $serieRepository->find($idEntity);
                $watchlistItem->setSerie($serie);
            }
            else if($type == "season")
            {
                $season = $seasonRepository->find($idEntity);
                $watchlistItem->setSeason($season);
            }
            else if($type == "episode")
            {
                $episode = $episodeRepository->find($idEntity);
                $watchlistItem->setEpisode($episode);
            }
            else if($type == "saga")
            {
                $saga = $sagaRepository->find($idEntity);
                $watchlistItem->setSaga($saga);
            }
            else
            {
                return $this->redirect('/');
            }

            $watchlistItemRepository->save($watchlistItem, true);
        }

        return $this->redirectToRoute('app_watchlist_show', ['id' => $watchlist->getId()]);
    }

    #[Route('/{id}', name: 'app_watchlist_item_show', methods: ['GET'])]
    public function show(Int $id,
                         WatchlistItemRepository $watchlistItemRepository,
                         MovieParsing $movieParsing,
                         SerieParsing $serieParsing,
                         SeasonParsing $seasonParsing,
                         EpisodeParsing $episodeParsing,
                         SagaParsing $sagaParsing
    ): Response
    {
        $watchlistItem = $watchlistItemRepository->find($id);

        switch($watchlistItem->getItemType())
        {
            case 'movie':
                $watchlistCard = $movieParsing->movieWatchlistCardParsing($watchlistItem->getId(), $watchlistItem->getMovie());
                break;

            case 'serie':
                $watchlistCard = $serieParsing->serieWatchlistCardParsing($watchlistItem->getId(),$watchlistItem->getSerie());
                break;

            case 'season':
                $watchlistCard = $seasonParsing->seasonWatchlistCardParsing($watchlistItem->getId(),$watchlistItem->getSeason());
                break;

            case 'episode':
                $watchlistCard = $episodeParsing->episodeWatchlistCardParsing($watchlistItem->getId(),$watchlistItem->getEpisode());
                break;

            case 'saga':
                $watchlistCard = $sagaParsing->sagaWatchlistCardParsing($watchlistItem->getId(),$watchlistItem->getSaga());
                break;
        }

        return $this->render('watchlist_item/show.html.twig', [
            'watchlistCard' => $watchlistCard,
        ]);
    }
}
