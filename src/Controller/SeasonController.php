<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SeasonParsing;

#[Route('/season')]
class SeasonController extends AbstractController
{
    #[Route('/{id}', name: 'app_season_show', methods: ['GET'])]
    public function show(String $id, SeasonParsing $seasonParsing): Response
    {
        $ids = explode("-", $id);

        return $this->render('season/show.html.twig', [
            'season' => $seasonParsing->seasonParsing($ids[0], $ids[1]),
        ]);
    }
}
