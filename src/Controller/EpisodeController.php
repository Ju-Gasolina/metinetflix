<?php

namespace App\Controller;

use App\Service\EpisodeParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/episode')]
class EpisodeController extends AbstractController
{
    #[Route('/{id}', name: 'app_episode_show', methods: ['GET'])]
    public function show(String $id, EpisodeParsing $episodeParsing): Response
    {
        $ids = explode("-", $id);

        return $this->render('episode/show.html.twig', [
            'episode' => $episodeParsing->episodeParsing($ids[0], $ids[1], $ids[2]),
        ]);
    }
}
