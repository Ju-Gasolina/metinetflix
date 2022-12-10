<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SeasonParsing;

#[Route('/season')]
class SeasonController extends AbstractController
{
    #[Route('/{idTV}/{idTVSeason}', name: 'app_season_show', methods: ['GET'])]
    public function show(int $idTV, int $idTVSeason, SeasonParsing $seasonParsing): Response
    {
        return $this->render('season/show.html.twig', [
            'season' => $seasonParsing->seasonParsing($idTV, $idTVSeason),
        ]);
    }
}
