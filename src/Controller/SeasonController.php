<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Serie;
use App\Repository\SeasonRepository;
use App\Service\SeasonParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

                $serie = new Serie();
                $serie->setGenres(json_encode($result2['genres']));
                $serie->setName($result2['original_name']);
                $serie->setIdTMDB($result2['id']);
                $serieRepository->save($serie, true);
            }

            //Season API request
            $result = $seasonParsing->seasonParsing($ids[0], $ids[1]);

            $season = new Season();
            $season->setName($result['name']);
            $season->setSerie($serie);
            $season->setIdTMDB($idTMDB);
            $seasonRepository->save($season, true);
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'season', 'idEntity' => $season->getId()]);
    }

    #[Route('/{id}', name: 'app_season_show', methods: ['GET'])]
    public function show(String $id, SeasonParsing $seasonParsing): Response
    {
        $ids = explode("-", $id);

        return $this->render('season/show.html.twig', [
            'season' => $seasonParsing->seasonParsing($ids[0], $ids[1]),
        ]);
    }
}
