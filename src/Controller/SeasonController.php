<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Serie;
use App\Repository\SeasonRepository;
use App\Repository\WatchlistRepository;
use App\Repository\WatchlistItemRepository;
use App\Repository\EpisodeRepository;
use App\Service\SeasonParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

use App\Repository\SerieRepository;
use App\Service\SerieParsing;

#[Route('/season')]
class SeasonController extends AbstractController
{
    #[Route('/new/{idTMDB}', name: 'app_season_new', methods: ['GET'])]
    public function new(String $idTMDB, SeasonParsing $seasonParsing, SeasonRepository $seasonRepository, SerieParsing $serieParsing, SerieRepository $serieRepository): Response
    {
        //Season find request
        $season = $seasonRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(!isset($season))
        {
            //ID parsing
            $ids = explode("-", $idTMDB);

            //Serie find Request
            $serie = $serieRepository->findOneBy(["idTMDB" => $ids[0]]);

            if(!isset($serie))
            {
                //Serie API request
                $result2 = $serieParsing->serieParsing($ids[0]);

                //Serie creation
                $serie = new Serie();
                $serie->setGenres(json_encode($result2['genres']));
                $serie->setName($result2['original_name']);
                $serie->setFirstAirDate($result2['first_air_date']);
                $serie->setPosterPath($result2['poster_path']);
                $serie->setIdTMDB($result2['id']);
                $serieRepository->save($serie, true);
            }

            //Season API request
            $result = $seasonParsing->seasonParsing($ids[0], $ids[1]);

            //Season creation
            $season = new Season();
            $season->setName($result['name']);
            $season->setSerie($serie);
            $season->setAirDate($result['air_date']);
            $season->setPosterPath($result['poster_path']);
            $season->setIdTMDB($idTMDB);
            $seasonRepository->save($season, true);
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'season', 'idEntity' => $season->getId()]);
    }

    #[Route('/{id}', name: 'app_season_show', methods: ['GET'])]
    public function show(String $id, SeasonParsing $seasonParsing, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository): Response
    {
        $ids = explode("-", $id);

        $season = $seasonParsing->seasonParsing($ids[0], $ids[1]);

        $season["isWatchlistItem"] = $this->isWatchlistItem($id, $security, $watchlistRepository, $watchlistItemRepository, $seasonRepository);

        foreach($season["episodes"] as $episode) {
            $episode->setIsWatchlistItem($this->isWatchlistItemEpisode($episode->getId(), $security, $watchlistRepository, $watchlistItemRepository, $episodeRepository));
        }

        return $this->render('season/show.html.twig', [
            'season' => $season,
        ]);
    }

    private function isWatchlistItem(string $idTMDB, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, SeasonRepository $seasonRepository): bool
    {
        if(!$this->isGranted('ROLE_USER')) return false;

        $searchSeason = $seasonRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(isset($searchSeason))
        {
            $result = $watchlistItemRepository->findOneBy([
                "watchlist" => $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()]),
                "season" => $searchSeason->getId()
            ]);

            if(isset($result)) return true;
            else return false;
        }
        else return false;
    }

    private function isWatchlistItemEpisode(string $idTMDB, Security $security, WatchlistRepository $watchlistRepository, WatchlistItemRepository $watchlistItemRepository, EpisodeRepository $episodeRepository): bool
    {
        if(!$this->isGranted('ROLE_USER')) return false;

        $searchEpisode = $episodeRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(isset($searchEpisode))
        {
            $result = $watchlistItemRepository->findOneBy([
                "watchlist" => $watchlistRepository->findOneBy(["user" => $security->getUser()->getId()]),
                "episode" => $searchEpisode->getId()
            ]);

            if(isset($result)) return true;
            else return false;
        }
        else return false;
    }
}
