<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Serie;
use App\Repository\EpisodeRepository;
use App\Repository\SeasonRepository;
use App\Repository\SerieRepository;
use App\Service\EpisodeParsing;
use App\Service\SeasonParsing;
use App\Service\SerieParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/episode')]
class EpisodeController extends AbstractController
{
    #[Route('/new/{idTMDB}', name: 'app_episode_new', methods: ['GET'])]
    public function new(String $idTMDB, EpisodeParsing $episodeParsing, EpisodeRepository $episodeRepository, SeasonParsing $seasonParsing, SeasonRepository $seasonRepository, SerieParsing $serieParsing, SerieRepository $serieRepository): Response
    {
        //Episode find request
        $episode = $episodeRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(!isset($episode))
        {
            //ID parsing
            $ids = explode("-", $idTMDB);

            //Serie find Request
            $serie = $serieRepository->findOneBy(["idTMDB" => $ids[0]]);

            if(!isset($serie))
            {
                //Serie API request
                $result3 = $serieParsing->serieParsing($ids[0]);

                //Serie creation
                $serie = new Serie();
                $serie->setGenres(json_encode($result3['genres']));
                $serie->setName($result3['original_name']);
                $serie->setFirstAirDate($result3['first_air_date']);
                $serie->setPosterPath($result3['poster_path']);
                $serie->setIdTMDB($result3['id']);
                $serieRepository->save($serie, true);
            }

            //Season find request
            $season = $seasonRepository->findOneBy(["idTMDB" => $idTMDB]);

            if(!isset($season))
            {
                //Season API request
                $result2 = $seasonParsing->seasonParsing($ids[0], $ids[1]);

                //Season creation
                $season = new Season();
                $season->setName($result2['name']);
                $season->setSerie($serie);
                $season->setAirDate($result2['air_date']);
                $season->setPosterPath($result2['poster_path']);
                $season->setIdTMDB($idTMDB);
                $seasonRepository->save($season, true);
            }

            //Episode API request
            $result = $episodeParsing->episodeParsing($ids[0], $ids[1], $ids[2]);

            //Episode creation
            $episode = new Episode();
            $episode->setName($result['name']);
            $episode->setSerie($serie);
            $episode->setSeason($season);
            $episode->setAirDate($result['air_date']);
            $episode->setPosterPath($result['poster_path']);
            $episode->setIdTMDB($idTMDB);
            $episodeRepository->save($episode, true);
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'episode', 'idEntity' => $episode->getId()]);
    }

    #[Route('/{id}', name: 'app_episode_show', methods: ['GET'])]
    public function show(String $id, EpisodeParsing $episodeParsing): Response
    {
        $ids = explode("-", $id);

        return $this->render('episode/show.html.twig', [
            'episode' => $episodeParsing->episodeParsing($ids[0], $ids[1], $ids[2]),
        ]);
    }
}
